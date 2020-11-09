import gmpy2
from Crypto.Util.number import *

flag = 'nyctf{****************************}'

m = int(flag.encode('hex'), 16)
p = getPrime(414)
q = getPrime(414)
e = 3
n = p * q
phi_n = (p - 1) * (q - 1)
d = gmpy2.invert(e, phi_n)
c = pow(m, e, n)
msg = "n = " + str(n) +"\nc = " + str(hex(c)[2:])

m = int(msg.encode('hex'), 16)
p = getPrime(2048)
q = getPrime(2048)
e = 32767
n = p * q
phi_n = (p - 1) * (q - 1)
d = gmpy2.invert(e, phi_n)
c = pow(m, e, n)
msg = "p = " + str(p) + "\nq = " + str(q) + "\nn = " + str(n) + "\nc = " + str(hex(c)[2:])
print msg
