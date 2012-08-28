import pylibmc
from pylibmc.test import make_test_client
from tests import PylibmcTestCase
from nose.tools import eq_

class ClientTests(PylibmcTestCase):
    def test_zerokey(self):
        bc = make_test_client(binary=True)
        k = "\x00\x01"
        assert bc.set(k, "test")
        rk = bc.get_multi([k]).keys()[0]
        eq_(k, rk)
