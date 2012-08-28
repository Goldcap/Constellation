using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using com.Akamai.EdgeAuth;
using System.Net;

namespace TokenAuthTest
{
    class Program
    {
        static void Main(string[] args)
        {
            //var sim = new Simulator();
            //sim.StartTest();
            //return;

            var test = new UnitTest();
            Console.WriteLine("Starting tests {0}", Environment.NewLine);
            Console.WriteLine("POSITIVE CASES:{0}", Environment.NewLine);
            Console.WriteLine("Big Token: {0}{1}", test.GetRealyBigToken(), Environment.NewLine);
            var basicToken = test.GetBasicToken();
            
            Console.WriteLine("Basic Token: {0}{1}", new Uri(basicToken, UriKind.RelativeOrAbsolute), Environment.NewLine);
            Console.WriteLine("Basic Token: {0}{1}", basicToken, Environment.NewLine);
            Console.WriteLine("Token Appended to URL: {0}{1}", test.GetTokenAppendedToUrl(), Environment.NewLine);
            Console.WriteLine("Token for a specific URL not just an ACL: {0}{1}", test.GetSpecificUrlToken(), Environment.NewLine);
            Console.WriteLine("Token with default StartTime: {0}{1}", test.GetTokenWithDefaultStartTime(), Environment.NewLine);
            Console.WriteLine("Token with simple key: {0}{1}", test.GetTokenWithSimpleKey(), Environment.NewLine);

            Console.WriteLine("NEGATIVE CASES:{0}", Environment.NewLine);
            Console.WriteLine("Token with invalid length key: {0}{1}", test.GetTokenWithInvalidLengthKey(), Environment.NewLine);
            Console.WriteLine("Token with invalid non-alphanum key: {0}{1}", test.GetTokenWithInvalidNonAlphaNumKey(), Environment.NewLine);
            Console.WriteLine("Token with invalid key: {0}{1}", test.GetTokenWithInvalidKey(), Environment.NewLine);

            Console.ReadLine();
        }
    }

    class Simulator
    {
        internal void StartTest()
        {
            List<string> urlsToTest = new List<string> { 
                "https://hlslivebeta-f.akamaihd.net/hls/live/47117/security/test/pcJune6_1.m3u8",
                "http://publisher.edgesuite.net/crossdomain.xml",
                "http://pankaj.com/crossdomain.xml" };

            string token;
            Uri tokenUrl;

            token = AkamaiTokenGenerator.GenerateToken(new AkamaiTokenConfig { Window = 86400, PreEscapeAcl = false });

            foreach (var urlToTest in urlsToTest)
            {
                tokenUrl = new Uri(urlToTest + "?hdnea=" + token, UriKind.Absolute);
                Console.WriteLine("{1}GETting {0}", tokenUrl, Environment.NewLine);

                GetUrl(tokenUrl);
            }

            Console.ReadLine();
        }

        private void GetUrl(Uri url)
        {
            WebClient wc = new WebClient();
            try
            {
                wc.OpenRead(url);
                Console.WriteLine("GET Completed");
            }
            catch (WebException ex)
            {
                Console.WriteLine(ex);
            }
        }
    }
}