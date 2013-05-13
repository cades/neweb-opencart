# neweb-opencart
藍新科技是台灣國內的一家金流服務商. 由於官方未提供opencart的模組, 就自己刻了一個.
藍新有信用卡與非信用卡兩個API, 這個專案是信用卡模組.

# 如何參與
由於 opencart 官方撰寫模組的教學文件不夠詳盡, 遂花了很多時間在 google 範例. 以下是我搜集到的優良資源:

## 了解服務商API
官方API的文件和範例程式可以到 http://service.neweb.com.tw/1-3-17.html 下載, 帳號/密碼 : download / merchant  

## 學習撰寫 opencart module
以下列出幾個幫助我很多的教學:
1. [opencart官方對模組的介紹](http://docs.opencart.com/display/opencart/Developing+modules)
2. [DIY模組教學](http://opencart.hostjars.com/blog/3), 和作者寫的[Startup Module](http://opencart.hostjars.com/creating-opencart-modules). 作者很用心, 程式碼中的註解會很友善的引導你了解一個模組的架構.
3. [2012年底的文章](http://www.mzcart.com/open-cart-how-to-create-a-payment-module-for-open-cart/), 極優. 有callback寫法可供參考. 非常詳盡, 或許有些功能暫時用不到, 但往後如有需要, 可以來這裡尋寶.
4. [這篇文章](http://forum.opencart.com/viewtopic.php?f=136&t=30653)提到有兩種付款模式：gateway hosted 與merchant hosted
5. 參考 [SmilePay寫好的模組](http://www.smilepay.net/download/index_module.asp) 也幫了我很大的忙

歡迎在 github 上開issue, 或者發 pull request 給我:)

# Credits
這個模組是我在[有機誌](http://www.organic-magazine.com)上班期間完成的.

# LICENCE
MIT