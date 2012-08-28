#!/usr/bin/python

import sys
import re
import string
import os
import shutil
import urllib
import logging
from subprocess import Popen,PIPE
from UserString import MutableString

def shellVars2Dict(filename):
	'''Reads a file containing lines with <KEY>=<VALUE> pairs and turns it into a dict'''
	f = open(filename, 'r')
	lines = f.readlines();
	result = { }

	for line in lines:
		parts = line.strip().partition('=')
		key = parts[0].strip()
		val = parts[2].strip()
		if key:
			result[key] = val
	
	return result

def usage():	
	print "Usage: mount_ebs_volume.py <action: mount or unmount> <ebs_volume_id> <device_name> <fs_type> <mount_point>"

def mount():
	logging.info('Attempting to attach volume [%s] to instance [%s] as [%s]' % (volume_name, instance_id, device_name))
	cmd = 'ec2-attach-volume -C %s -K %s %s -i %s -d %s' % (ec2conf['CERT'], ec2conf['PRIVKEY'], volume_name, instance_id, device_name)

	for i in range(0, 20):
		p = Popen(cmd, shell=True,stdout=PIPE);
		exitcode = p.wait() & 0xff;    # exit code is in the high byte

		if exitcode != 0:
			logging.warning('ec2-attach-volume exited with code %d. Aborting' % exitcode) 
			break

		result = p.stdout.read()
		m = regex_attaching.match(result)
		if m:
			_device_id = m.group('device_id')
			_volume_id= m.group('volume_id')
			logging.info('Volume [%s] attaching to device [%s] in attempt #%d' % (_volume_id, _device_id, i))

			# wait for fully attached
			cmd = 'ec2-describe-volumes -C %s -K %s' % (ec2conf['CERT'], ec2conf['PRIVKEY'])
			for n in range(0, 20):
				logging.debug('Running ec2-describe-volumes for the %dth time' % n)
				p = Popen(cmd, shell=True,stdout=PIPE);
				exitcode = p.wait() & 0xff;    # exit code is in the high byte
				if exitcode != 0:
					sys.exit(1)
				
				result = p.stdout.read()
				m = regex_attached.search(result)
				if m:
					logging.info('Volume [%s] attached to device [%s] in attempt #%d' % (_volume_id, _device_id, i))
					break

			# device attached, create mount point if necessary
			if(os.access(mount_point, os.F_OK) == False):
				os.makedirs(mount_point)

			# mount 
			cmd = 'mount -t %s -o noatime %s %s' % (fs_type, _device_id, mount_point)
			p = Popen(cmd, shell=True,stdout=PIPE);
			exitcode = p.wait() & 0xff;    # exit code is in the high byte
			logging.debug('mount returned status code %d' % exitcode)
			if exitcode == 0:
				logging.info('Device [%s] mounted as [%s] in attempt #%d' % (_device_id, mount_point, i))
				break

def unmount():
	logging.info('Attempting to detach volume [%s] from instance [%s] as [%s]' % (volume_name, instance_id, device_name))

	cmd = 'ec2-detach-volume -C %s -K %s %s -i %s -d %s' % (ec2conf['CERT'], ec2conf['PRIVKEY'], volume_name, instance_id, device_name)
	p = Popen(cmd, shell=True,stdout=PIPE);
	exitcode = p.wait() & 0xff;    # exit code is in the high byte
	logging.debug('ec2-detach-volume returned status code %d' % exitcode)
	if exitcode == 0:
		logging.info('Successfully detached volume [%s] from instance [%s] as [%s]' % (volume_name, instance_id, device_name))

	
# arguments
if len(sys.argv) < 5:
	usage()
	sys.exit(0)

action = sys.argv[1]
if action != 'mount' and action != 'unmount':
	usage()
	sys.exit(1)

volume_name = sys.argv[2]
instance_id = None
device_name = sys.argv[3]
fs_type = sys.argv[4]
mount_point = sys.argv[5]
regex_attaching = re.compile('^ATTACHMENT\s+(?P<volume_id>\S+)\s+(?P<instance_id>\S+)\s+(?P<device_id>\S+)\s+attaching')
regex_attached = re.compile('^ATTACHMENT\s+(?P<volume_id>\S+)\s+(?P<instance_id>\S+)\s+(?P<device_id>\S+)\s+attached', re.MULTILINE)

# Logging setup
formatter = logging.Formatter("[%(levelname)s:%(name)s] %(module)s:%(lineno)d %(asctime)s: %(message)s")
ch = logging.StreamHandler()
ch.setLevel(logging.DEBUG)	# accepts all levels
ch.setFormatter(formatter)
ch2 = logging.FileHandler('/var/log/ebs_mount.log')
ch2.setLevel(logging.DEBUG)	# accepts all levels
ch2.setFormatter(formatter)
logger = logging.root
logger.addHandler(ch)
logger.addHandler(ch2)
logger.setLevel(logging.INFO)

# setup environment
os.putenv('PATH', '/root/ec2-api-tools/bin/:' + os.getenv('PATH'))
os.putenv('EC2_HOME', '/root/ec2-api-tools/')
os.putenv('JAVA_HOME', '/usr/lib/jvm/java-6-sun')

# EC2 conf
ec2conf = shellVars2Dict('/root/.ec2cred')

# get instance id
for i in range(0, 5):
	try:
		fp = urllib.urlopen('http://169.254.169.254/1.0/meta-data/instance-id')
		instance_id = fp.read()
		fp.close()
		if instance_id:
			break
	except IOError:
		pass

if not instance_id:
	logging.error('Unable to determine instance id')
	sys.exit(1)

logging.info('instance_id = %s' % instance_id)

if action == 'mount':
	mount()
elif action == 'unmount':
	unmount()
