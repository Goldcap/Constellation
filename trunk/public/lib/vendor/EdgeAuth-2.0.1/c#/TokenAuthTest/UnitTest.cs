using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using com.Akamai.EdgeAuth;

namespace TokenAuthTest
{
    class UnitTest
    {
        internal string GetRealyBigToken()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACSHA256;
            conf.IP = "127.0.0.1";
            conf.StartTime = 1294788122;
            conf.Window = 86400;
            conf.SessionID = "11729319801";
            conf.Payload = "THIS_WILL_BE_INCLUDED_IN_TOKEN";
            conf.Salt = "My User Agent goes here";
            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetBasicToken()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACMD5;
            conf.StartTime = 1294788122;
            conf.Window = 86400;
            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenAppendedToUrl()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACSHA1;
            conf.StartTime = 1294788122;
            conf.Window = 86400;
            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetSpecificUrlToken()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACSHA256;
            conf.StartTime = 1294788122;
            conf.Window = 86400;
            conf.Acl = "/crossdomain.xml";
            conf.IsUrl = true;
            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenWithDefaultStartTime()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACSHA256;
            conf.Window = 300;  // 300 seconds.

            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenWithSimpleKey()
        {
            AkamaiTokenConfig conf = new AkamaiTokenConfig();
            conf.TokenAlgorithm = Algorithm.HMACSHA256;
            conf.Window = 300;  // 300 seconds.
            conf.Key = "ab09";

            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenWithInvalidLengthKey()
        {
            AkamaiTokenConfig conf = null;
            try
            {
                conf = new AkamaiTokenConfig();
                conf.TokenAlgorithm = Algorithm.HMACSHA256;
                conf.Window = 300;  // 300 seconds.
                conf.Key = "a";
            }
            catch (ArgumentException e)
            {
                return "Error:" + e.Message;
            }

            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenWithInvalidNonAlphaNumKey()
        {
            AkamaiTokenConfig conf = null;
            try
            {
                conf = new AkamaiTokenConfig();
                conf.TokenAlgorithm = Algorithm.HMACSHA256;
                conf.Window = 300;  // 300 seconds.
                conf.Key = "a&";
            }
            catch (ArgumentException e)
            {
                return "Error:" + e.Message;
            }

            return AkamaiTokenGenerator.GenerateToken(conf);
        }

        internal string GetTokenWithInvalidKey()
        {
            AkamaiTokenConfig conf = null;
            try
            {
                conf = new AkamaiTokenConfig();
                conf.TokenAlgorithm = Algorithm.HMACSHA256;
                conf.Window = 300;  // 300 seconds.
                conf.Key = string.Empty;
            }
            catch (ArgumentException e)
            {
                return "Error:" + e.Message;
            }

            return AkamaiTokenGenerator.GenerateToken(conf);
        }
    }
}