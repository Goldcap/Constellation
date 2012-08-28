using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace com.Akamai.EdgeAuth
{
    internal static class Extensions
    {
        public static byte[] ToByteArray(this string me)
        {
            int len = me.Length;
            byte[] data = new byte[len / 2];
            for (int i = 0; i < len; i += 2)
            {
                int val1 = -1, val2 = -1;

                try
                {
                    val1 = Convert.ToInt32(me[i].ToString(), 16) << 4;
                }
                catch (FormatException)
                {
                }
                catch (ArgumentException)
                {
                }

                try
                {
                    val2 = Convert.ToInt32(me[i + 1].ToString(), 16);
                }
                catch (FormatException)
                {
                }
                catch (ArgumentException)
                {
                }

                val1 += val2;
                data[i / 2] = Convert.ToByte(val1);
            }
            return data;
        }
    }
}
