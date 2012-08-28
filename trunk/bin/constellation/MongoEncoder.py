from bson.objectid import ObjectId
from json import JSONEncoder 

class MongoEncoder(JSONEncoder):
		def default(self, obj, **kwargs):
			if isinstance(obj, ObjectId):
				return str(obj)
			else:
				return JSONEncoder.default(obj, **kwargs)
