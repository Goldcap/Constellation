<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// CVS: $Id: bug-727-3.phpt,v 1.1 2006/11/20 08:57:08 firman Exp $
?>
--TEST--
Bug #727: Date_Calc::weeksInMonth() wrong result
Tests for weeksInMonth "random"
Sunday as 1st day of week
--FILE--
<?php
/**
 * Test for: Date_Calc
 * Parts tested: Date_Calc::weeksInMonth()
 */

/**
 * Sunday as 1st day of week
 */
define('DATE_CALC_BEGIN_WEEKDAY', 0);

require_once "Date/Calc.php";

$tests = array(
    array(1999, 12), array(2000, 11), array(2001, 11), array(2002, 12),
    array(2003, 12), array(2004, 12), array(2005, 12), array(2006, 11),
    array(2007, 11), array(2008, 12), array(2009, 12), array(2010, 12),
    array(2011, 12), array(2012, 11), array(2013, 12), array(2014, 12),
    array(2015, 12), array(2016, 12), array(2017, 11), array(2018, 11),
    array(2019, 12), array(2020, 12), array(2021, 12), array(2022, 12),
    array(2023, 11), array(2024, 12), array(2025, 12), array(2026, 12),
    array(2027, 12), array(2028, 11), array(2029, 11), array(2030, 12),
    array(2031, 12), array(2032, 12), array(2033, 12), array(2034, 11),
    array(2035, 11), array(2036, 12), array(2037, 12), array(1930, 12),
    array(1931, 12), array(1932, 12), array(1933, 11), array(1934, 11),
    array(1935, 12), array(1936, 12), array(1937, 12), array(1938, 12),
    array(1939, 11), array(1940, 12), array(1941, 12), array(1942, 12),
    array(1943, 12), array(1944, 11), array(1945, 11), array(1946, 12),
    array(1947, 12), array(1948, 12), array(1949, 12), array(1800, 12),
    array(1801, 12), array(1802, 12), array(1803, 12), array(1804, 11),
    array(1805, 12), array(1806, 12), array(1807, 12), array(1808, 12),
    array(1809, 11), array(1810, 11), array(1811, 12), array(1812, 12),
    array(1813, 12), array(1814, 12), array(1815, 11), array(1816, 12),
    array(1817, 12), array(1818, 12), array(1819, 12), array(1820, 11),
    array(1821, 11), array(1822, 12), array(1823, 12), array(1824, 12),
    array(1825, 12), array(1826, 11), array(1827, 11), array(1828, 12),
    array(1829, 12), array(1830, 12), array(1831, 12), array(1832, 11),
    array(1833, 12), array(1834, 12), array(1835, 12), array(1836, 12),
    array(1837, 11), array(1838, 11), array(1839, 12), array(1840, 12),
    array(1841, 12), array(1842, 12), array(1843, 11), array(1844, 12),
    array(1845, 12), array(1846, 12), array(1847, 12), array(1848, 11),
    array(1849, 11), array(1850, 12), array(1851, 12), array(1852, 12),
    array(1853, 12), array(1854, 11), array(1855, 11), array(1856, 12),
    array(1857, 12), array(1858, 12), array(1859, 12), array(1860, 11),
    array(1861, 12), array(1862, 12), array(1863, 12), array(1864, 12),
    array(1865, 11), array(1866, 11), array(1867, 12), array(1868, 12),
    array(1869, 12), array(1870, 12), array(1871, 11), array(1872, 12),
    array(1873, 12), array(1874, 12), array(1875, 12), array(1876, 11),
    array(1877, 11), array(1878, 12), array(1879, 12), array(1880, 12),
    array(1881, 12), array(1882, 11), array(1883, 11), array(1884, 12),
    array(1885, 12), array(1886, 12), array(1887, 12), array(1888, 11),
    array(1889, 12), array(1890, 12), array(1891, 12), array(1892, 12),
    array(1893, 11), array(1894, 11), array(1895, 12), array(1896, 12),
    array(1897, 12), array(1898, 12), array(1899, 11), array(1900, 11),
    array(1901, 12), array(1902, 12), array(1903, 12), array(1904, 12),
    array(1905, 11), array(1906, 11), array(1907, 12), array(1908, 12),
    array(1909, 12), array(1910, 12), array(1911, 11), array(1912, 12),
    array(1913, 12), array(1914, 12), array(1915, 12), array(1916, 11),
    array(1917, 11), array(1918, 12), array(1919, 12), array(1920, 12),
    array(1921, 12), array(1922, 11), array(1923, 11), array(1924, 12),
    array(1925, 12), array(1926, 12), array(1927, 12), array(1928, 11),
    array(1929, 12), array(1999, 10), array(2000, 12), array(2001, 12),
    array(2002, 6),  array(2003, 11), array(2004, 10), array(2005, 10),
    array(2006, 12), array(2007, 12), array(2008, 11), array(2009, 8),
    array(2010, 10), array(2011, 10), array(2012, 12), array(2013, 6),
    array(2014, 11), array(2015, 8),  array(2016, 10), array(2017, 12),
    array(2018, 12), array(2019, 6),  array(2020, 8),  array(2021, 10),
    array(2022, 10), array(2023, 12), array(2024, 6),  array(2025, 11),
    array(2026, 8),  array(2027, 10), array(2028, 12), array(2029, 12),
    array(2030, 6),  array(2031, 11), array(2032, 10), array(2033, 10),
    array(2034, 12), array(2035, 12), array(2036, 11), array(2037, 8),
    array(1930, 11), array(1931, 8),  array(1932, 10), array(1933, 12),
    array(1934, 12), array(1935, 6),  array(1936, 8),  array(1937, 10),
    array(1938, 10), array(1939, 12), array(1940, 6),  array(1941, 11),
    array(1942, 8),  array(1943, 10), array(1944, 12), array(1945, 12),
    array(1946, 6),  array(1947, 11), array(1948, 10), array(1949, 10),
    array(1800, 11), array(1801, 8),  array(1802, 10), array(1803, 10),
    array(1804, 12), array(1805, 6),  array(1806, 11), array(1807, 8),
    array(1808, 10), array(1809, 12), array(1810, 12), array(1811, 6),
    array(1812, 8),  array(1813, 10), array(1814, 10), array(1815, 12),
    array(1816, 6),  array(1817, 11), array(1818, 8),  array(1819, 10),
    array(1820, 12), array(1821, 12), array(1822, 6),  array(1823, 11),
    array(1824, 10), array(1825, 10), array(1826, 12), array(1827, 12),
    array(1828, 11), array(1829, 8),  array(1830, 10), array(1831, 10),
    array(1832, 12), array(1833, 6),  array(1834, 11), array(1835, 8),
    array(1836, 10), array(1837, 12), array(1838, 12), array(1839, 6),
    array(1840, 8),  array(1841, 10), array(1842, 10), array(1843, 12),
    array(1844, 6),  array(1845, 11), array(1846, 8),  array(1847, 10),
    array(1848, 12), array(1849, 12), array(1850, 6),  array(1851, 11),
    array(1852, 10), array(1853, 10), array(1854, 12), array(1855, 12),
    array(1856, 11), array(1857, 8),  array(1858, 10), array(1859, 10),
    array(1860, 12), array(1861, 6),  array(1862, 11), array(1863, 8),
    array(1864, 10), array(1865, 12), array(1866, 12), array(1867, 6),
    array(1868, 8),  array(1869, 10), array(1870, 10), array(1871, 12),
    array(1872, 6),  array(1873, 11), array(1874, 8),  array(1875, 10),
    array(1876, 12), array(1877, 12), array(1878, 6),  array(1879, 11),
    array(1880, 10), array(1881, 10), array(1882, 12), array(1883, 12),
    array(1884, 11), array(1885, 8),  array(1886, 10), array(1887, 10),
    array(1888, 12), array(1889, 6),  array(1890, 11), array(1891, 8),
    array(1892, 10), array(1893, 12), array(1894, 12), array(1895, 6),
    array(1896, 8),  array(1897, 10), array(1898, 10), array(1899, 12),
    array(1900, 12), array(1901, 6),  array(1902, 11), array(1903, 8),
    array(1904, 10), array(1905, 12), array(1906, 12), array(1907, 6),
    array(1908, 8),  array(1909, 10), array(1910, 10), array(1911, 12),
    array(1912, 6),  array(1913, 11), array(1914, 8),  array(1915, 10),
    array(1916, 12), array(1917, 12), array(1918, 6),  array(1919, 11),
    array(1920, 10), array(1921, 10), array(1922, 12), array(1923, 12),
    array(1924, 11), array(1925, 8),  array(1926, 10), array(1927, 10),
    array(1928, 12), array(1929, 6)
);

foreach ($tests as $date) {
    list ($year, $month) = $date;
    echo $year . '/' . $month . ' = ' . Date_Calc::weeksInMonth($month, $year) . ' weeks' . "\n";
}
?>
--EXPECT--
1999/12 = 5 weeks
2000/11 = 5 weeks
2001/11 = 5 weeks
2002/12 = 5 weeks
2003/12 = 5 weeks
2004/12 = 5 weeks
2005/12 = 5 weeks
2006/11 = 5 weeks
2007/11 = 5 weeks
2008/12 = 5 weeks
2009/12 = 5 weeks
2010/12 = 5 weeks
2011/12 = 5 weeks
2012/11 = 5 weeks
2013/12 = 5 weeks
2014/12 = 5 weeks
2015/12 = 5 weeks
2016/12 = 5 weeks
2017/11 = 5 weeks
2018/11 = 5 weeks
2019/12 = 5 weeks
2020/12 = 5 weeks
2021/12 = 5 weeks
2022/12 = 5 weeks
2023/11 = 5 weeks
2024/12 = 5 weeks
2025/12 = 5 weeks
2026/12 = 5 weeks
2027/12 = 5 weeks
2028/11 = 5 weeks
2029/11 = 5 weeks
2030/12 = 5 weeks
2031/12 = 5 weeks
2032/12 = 5 weeks
2033/12 = 5 weeks
2034/11 = 5 weeks
2035/11 = 5 weeks
2036/12 = 5 weeks
2037/12 = 5 weeks
1930/12 = 5 weeks
1931/12 = 5 weeks
1932/12 = 5 weeks
1933/11 = 5 weeks
1934/11 = 5 weeks
1935/12 = 5 weeks
1936/12 = 5 weeks
1937/12 = 5 weeks
1938/12 = 5 weeks
1939/11 = 5 weeks
1940/12 = 5 weeks
1941/12 = 5 weeks
1942/12 = 5 weeks
1943/12 = 5 weeks
1944/11 = 5 weeks
1945/11 = 5 weeks
1946/12 = 5 weeks
1947/12 = 5 weeks
1948/12 = 5 weeks
1949/12 = 5 weeks
1800/12 = 5 weeks
1801/12 = 5 weeks
1802/12 = 5 weeks
1803/12 = 5 weeks
1804/11 = 5 weeks
1805/12 = 5 weeks
1806/12 = 5 weeks
1807/12 = 5 weeks
1808/12 = 5 weeks
1809/11 = 5 weeks
1810/11 = 5 weeks
1811/12 = 5 weeks
1812/12 = 5 weeks
1813/12 = 5 weeks
1814/12 = 5 weeks
1815/11 = 5 weeks
1816/12 = 5 weeks
1817/12 = 5 weeks
1818/12 = 5 weeks
1819/12 = 5 weeks
1820/11 = 5 weeks
1821/11 = 5 weeks
1822/12 = 5 weeks
1823/12 = 5 weeks
1824/12 = 5 weeks
1825/12 = 5 weeks
1826/11 = 5 weeks
1827/11 = 5 weeks
1828/12 = 5 weeks
1829/12 = 5 weeks
1830/12 = 5 weeks
1831/12 = 5 weeks
1832/11 = 5 weeks
1833/12 = 5 weeks
1834/12 = 5 weeks
1835/12 = 5 weeks
1836/12 = 5 weeks
1837/11 = 5 weeks
1838/11 = 5 weeks
1839/12 = 5 weeks
1840/12 = 5 weeks
1841/12 = 5 weeks
1842/12 = 5 weeks
1843/11 = 5 weeks
1844/12 = 5 weeks
1845/12 = 5 weeks
1846/12 = 5 weeks
1847/12 = 5 weeks
1848/11 = 5 weeks
1849/11 = 5 weeks
1850/12 = 5 weeks
1851/12 = 5 weeks
1852/12 = 5 weeks
1853/12 = 5 weeks
1854/11 = 5 weeks
1855/11 = 5 weeks
1856/12 = 5 weeks
1857/12 = 5 weeks
1858/12 = 5 weeks
1859/12 = 5 weeks
1860/11 = 5 weeks
1861/12 = 5 weeks
1862/12 = 5 weeks
1863/12 = 5 weeks
1864/12 = 5 weeks
1865/11 = 5 weeks
1866/11 = 5 weeks
1867/12 = 5 weeks
1868/12 = 5 weeks
1869/12 = 5 weeks
1870/12 = 5 weeks
1871/11 = 5 weeks
1872/12 = 5 weeks
1873/12 = 5 weeks
1874/12 = 5 weeks
1875/12 = 5 weeks
1876/11 = 5 weeks
1877/11 = 5 weeks
1878/12 = 5 weeks
1879/12 = 5 weeks
1880/12 = 5 weeks
1881/12 = 5 weeks
1882/11 = 5 weeks
1883/11 = 5 weeks
1884/12 = 5 weeks
1885/12 = 5 weeks
1886/12 = 5 weeks
1887/12 = 5 weeks
1888/11 = 5 weeks
1889/12 = 5 weeks
1890/12 = 5 weeks
1891/12 = 5 weeks
1892/12 = 5 weeks
1893/11 = 5 weeks
1894/11 = 5 weeks
1895/12 = 5 weeks
1896/12 = 5 weeks
1897/12 = 5 weeks
1898/12 = 5 weeks
1899/11 = 5 weeks
1900/11 = 5 weeks
1901/12 = 5 weeks
1902/12 = 5 weeks
1903/12 = 5 weeks
1904/12 = 5 weeks
1905/11 = 5 weeks
1906/11 = 5 weeks
1907/12 = 5 weeks
1908/12 = 5 weeks
1909/12 = 5 weeks
1910/12 = 5 weeks
1911/11 = 5 weeks
1912/12 = 5 weeks
1913/12 = 5 weeks
1914/12 = 5 weeks
1915/12 = 5 weeks
1916/11 = 5 weeks
1917/11 = 5 weeks
1918/12 = 5 weeks
1919/12 = 5 weeks
1920/12 = 5 weeks
1921/12 = 5 weeks
1922/11 = 5 weeks
1923/11 = 5 weeks
1924/12 = 5 weeks
1925/12 = 5 weeks
1926/12 = 5 weeks
1927/12 = 5 weeks
1928/11 = 5 weeks
1929/12 = 5 weeks
1999/10 = 6 weeks
2000/12 = 6 weeks
2001/12 = 6 weeks
2002/6 = 6 weeks
2003/11 = 6 weeks
2004/10 = 6 weeks
2005/10 = 6 weeks
2006/12 = 6 weeks
2007/12 = 6 weeks
2008/11 = 6 weeks
2009/8 = 6 weeks
2010/10 = 6 weeks
2011/10 = 6 weeks
2012/12 = 6 weeks
2013/6 = 6 weeks
2014/11 = 6 weeks
2015/8 = 6 weeks
2016/10 = 6 weeks
2017/12 = 6 weeks
2018/12 = 6 weeks
2019/6 = 6 weeks
2020/8 = 6 weeks
2021/10 = 6 weeks
2022/10 = 6 weeks
2023/12 = 6 weeks
2024/6 = 6 weeks
2025/11 = 6 weeks
2026/8 = 6 weeks
2027/10 = 6 weeks
2028/12 = 6 weeks
2029/12 = 6 weeks
2030/6 = 6 weeks
2031/11 = 6 weeks
2032/10 = 6 weeks
2033/10 = 6 weeks
2034/12 = 6 weeks
2035/12 = 6 weeks
2036/11 = 6 weeks
2037/8 = 6 weeks
1930/11 = 6 weeks
1931/8 = 6 weeks
1932/10 = 6 weeks
1933/12 = 6 weeks
1934/12 = 6 weeks
1935/6 = 6 weeks
1936/8 = 6 weeks
1937/10 = 6 weeks
1938/10 = 6 weeks
1939/12 = 6 weeks
1940/6 = 6 weeks
1941/11 = 6 weeks
1942/8 = 6 weeks
1943/10 = 6 weeks
1944/12 = 6 weeks
1945/12 = 6 weeks
1946/6 = 6 weeks
1947/11 = 6 weeks
1948/10 = 6 weeks
1949/10 = 6 weeks
1800/11 = 6 weeks
1801/8 = 6 weeks
1802/10 = 6 weeks
1803/10 = 6 weeks
1804/12 = 6 weeks
1805/6 = 6 weeks
1806/11 = 6 weeks
1807/8 = 6 weeks
1808/10 = 6 weeks
1809/12 = 6 weeks
1810/12 = 6 weeks
1811/6 = 6 weeks
1812/8 = 6 weeks
1813/10 = 6 weeks
1814/10 = 6 weeks
1815/12 = 6 weeks
1816/6 = 6 weeks
1817/11 = 6 weeks
1818/8 = 6 weeks
1819/10 = 6 weeks
1820/12 = 6 weeks
1821/12 = 6 weeks
1822/6 = 6 weeks
1823/11 = 6 weeks
1824/10 = 6 weeks
1825/10 = 6 weeks
1826/12 = 6 weeks
1827/12 = 6 weeks
1828/11 = 6 weeks
1829/8 = 6 weeks
1830/10 = 6 weeks
1831/10 = 6 weeks
1832/12 = 6 weeks
1833/6 = 6 weeks
1834/11 = 6 weeks
1835/8 = 6 weeks
1836/10 = 6 weeks
1837/12 = 6 weeks
1838/12 = 6 weeks
1839/6 = 6 weeks
1840/8 = 6 weeks
1841/10 = 6 weeks
1842/10 = 6 weeks
1843/12 = 6 weeks
1844/6 = 6 weeks
1845/11 = 6 weeks
1846/8 = 6 weeks
1847/10 = 6 weeks
1848/12 = 6 weeks
1849/12 = 6 weeks
1850/6 = 6 weeks
1851/11 = 6 weeks
1852/10 = 6 weeks
1853/10 = 6 weeks
1854/12 = 6 weeks
1855/12 = 6 weeks
1856/11 = 6 weeks
1857/8 = 6 weeks
1858/10 = 6 weeks
1859/10 = 6 weeks
1860/12 = 6 weeks
1861/6 = 6 weeks
1862/11 = 6 weeks
1863/8 = 6 weeks
1864/10 = 6 weeks
1865/12 = 6 weeks
1866/12 = 6 weeks
1867/6 = 6 weeks
1868/8 = 6 weeks
1869/10 = 6 weeks
1870/10 = 6 weeks
1871/12 = 6 weeks
1872/6 = 6 weeks
1873/11 = 6 weeks
1874/8 = 6 weeks
1875/10 = 6 weeks
1876/12 = 6 weeks
1877/12 = 6 weeks
1878/6 = 6 weeks
1879/11 = 6 weeks
1880/10 = 6 weeks
1881/10 = 6 weeks
1882/12 = 6 weeks
1883/12 = 6 weeks
1884/11 = 6 weeks
1885/8 = 6 weeks
1886/10 = 6 weeks
1887/10 = 6 weeks
1888/12 = 6 weeks
1889/6 = 6 weeks
1890/11 = 6 weeks
1891/8 = 6 weeks
1892/10 = 6 weeks
1893/12 = 6 weeks
1894/12 = 6 weeks
1895/6 = 6 weeks
1896/8 = 6 weeks
1897/10 = 6 weeks
1898/10 = 6 weeks
1899/12 = 6 weeks
1900/12 = 6 weeks
1901/6 = 6 weeks
1902/11 = 6 weeks
1903/8 = 6 weeks
1904/10 = 6 weeks
1905/12 = 6 weeks
1906/12 = 6 weeks
1907/6 = 6 weeks
1908/8 = 6 weeks
1909/10 = 6 weeks
1910/10 = 6 weeks
1911/12 = 6 weeks
1912/6 = 6 weeks
1913/11 = 6 weeks
1914/8 = 6 weeks
1915/10 = 6 weeks
1916/12 = 6 weeks
1917/12 = 6 weeks
1918/6 = 6 weeks
1919/11 = 6 weeks
1920/10 = 6 weeks
1921/10 = 6 weeks
1922/12 = 6 weeks
1923/12 = 6 weeks
1924/11 = 6 weeks
1925/8 = 6 weeks
1926/10 = 6 weeks
1927/10 = 6 weeks
1928/12 = 6 weeks
1929/6 = 6 weeks
<?php
/*
 * Local variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>