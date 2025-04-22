<br />
<div align="center">
    <img src="https://doc.buildadmin.com/images/logo.png" alt="" />
    <h1 style="font-size: 36px;color: #2c3e50;font-weight: 600;margin: 0 0 6px 0;">BuildAdmin</h1>
    <p style="font-size: 17px;color: #6a8bad;margin-bottom: 10px;">使用流行技術棧快速創建商業級後台管理系統</p>
    <a href="https://uni.buildadmin.com" target="_blank">官網</a> |
    <a href="https://demo.buildadmin.com" target="_blank">演示</a> |
    <a href="https://ask.buildadmin.com" target="_blank">社區</a> |
    <a href="https://doc.buildadmin.com/" target="_blank">文檔</a> |
    <a href="https://jq.qq.com/?_wv=1027&k=c8a7iSk8" target="_blank">加群</a> |
    <a href="https://doc.buildadmin.com/guide/" target="_blank">視頻介紹</a> |
    <a href="https://gitee.com/wonderful-code/buildadmin" target="_blank">Gitee倉庫</a> |
    <a href="https://github.com/build-admin/BuildAdmin" target="_blank">GitHub倉庫</a>
</div>
<br />
<p align="center">
    <a href="https://www.thinkphp.cn/" target="_blank">
        <img src="https://img.shields.io/badge/ThinkPHP-%3E8.1-brightgreen?color=91aac3&labelColor=439EFD" alt="vue">
    </a>
    <a href="https://v3.vuejs.org/" target="_blank">
        <img src="https://img.shields.io/badge/Vue-%3E3.5-brightgreen?color=91aac3&labelColor=439EFD" alt="vue">
    </a>
    <a href="https://element-plus.org/zh-CN/guide/changelog.html" target="_blank">
        <img src="https://img.shields.io/badge/Element--Plus-%3E2.9-brightgreen?color=91aac3&labelColor=439EFD" alt="element plus">
    </a>
    <a href="https://www.tslang.cn/" target="_blank">
        <img src="https://img.shields.io/badge/TypeScript-%3E5.7-blue?color=91aac3&labelColor=439EFD" alt="typescript">
    </a>
    <a href="https://vitejs.dev/" target="_blank">
        <img src="https://img.shields.io/badge/Vite-%3E6.0-blue?color=91aac3&labelColor=439EFD" alt="vite">
    </a>
    <a href="https://pinia.vuejs.org/" target="_blank">
        <img src="https://img.shields.io/badge/Pinia-%3E2.3-blue?color=91aac3&labelColor=439EFD" alt="vite">
    </a>
    <a href="https://gitee.com/wonderful-code/buildadmin/blob/master/LICENSE" target="_blank">
        <img src="https://img.shields.io/badge/Apache2.0-license-blue?color=91aac3&labelColor=439EFD" alt="license">
    </a>
</p>

<br>
<div align="center">
  <img src="https://doc.buildadmin.com/images/readme/dashboard-radius.png" />
</div>
<br>

### 介紹
🌈 基於 Vue3.x + ThinkPHP8 + TypeScript + Vite + Pinia + Element Plus 等流行技術棧的後台管理系統，支持常駐內存運行、可視化CRUD代碼生成、自帶WEB終端、自適應多端、同時提供Web、WebNuxt、Server端、內置全局數據回收站和字段級數據修改保護、自動註冊路由、無限子級權限管理等，無需授權即可免費商用，希望能幫助大家實現快速開發。

### 主要特性
**🚀 CRUD代碼生成：**
圖形化拖拽生成後台增刪改查代碼，自動創建數據表；大氣且實用的表格，多達24種表單組件支持，行拖拽排序，受權限控制的編輯和刪除等等，並支持關聯表，可為您節省大量開發時間。

**💥 內置WEB終端：**
我們內置了一個WEB終端以實現一些理想中的功能，比如：雖然是基於vue3的系統，但你在安裝本系統時，並不需要手動執行 `npm install` 和 `npm build` 命令。且後續本終端將為您提供更多方便、快捷的服務。

**👍 流行且穩定的技術棧：**
除了基於 `ThinkPHP8` 前後端分離架構外，我們的 `Vue3` 使用了 `Setup` 狀態管理使用 `Pinia`，並使用了 `TypeScript、Vite` 等可以為你的知識面添磚加瓦的技術棧。

**🎨 模塊市場：**
一鍵安裝數據導入導出、短信發送、支付、雲存儲、富文本編輯器，甚至CMS、商城、社區、純前端技術棧的學習案例項目等，隨時隨地為系統添磚加瓦，系統能夠自動維護 `package.json` 和 `composer.json` 並通過內置終端自動完成模塊所需依賴的安裝，若您願意成為模塊開發者，模塊可以：覆蓋系統任何文件或為系統新增文件，您的模塊經由官方審核即可上架。

**🔀 前後端分離：**
項目的 `web` 文件夾內包含： `乾淨`（不含後端代碼）、`完整`（所有前端代碼文件均在此內）的前端代碼文件，代碼和部署均可前後分離，對前端開發者友好，作為純前端開發者，您可以將BAdmin當做學習與資源的社群，本系統可為您準備好案例和模板等所需要的環境，而您只需專注於學習或工作，不需要會任何後端代碼！（邀您：[和我們一起](https://jq.qq.com/?_wv=1027&k=c8a7iSk8) ）

**⚡️ 常駐內存：**
系統內置的功能均可常駐內存運行，享受比傳統框架快上數十倍的性能提升！目前 [Workerman模塊](https://modules.buildadmin.com/workerman) 可提供框架的常駐內存 `HTTP服務`，同時該模塊還提供了開箱即用的 `WebSocket服務`。

**🚚 按需加載：**
前端的頁面組件和語言包均是在使用到它們時，才從網絡異步加載，服務端則是基於 `TP8` 和 `PSR規範` 天生擁有真正的按需加載能力，所以，您無需考慮 `我並不需要多語言、我並不需要某個後台功能` 這類的問題，不需要不使用或隱藏即可。

**🌴 數據回收與反悔：**
內置全局數據回收站，並且提供字段級數據修改記錄和修改對比，隨時回滾和還原，安全且無感。

**✨ 高顏值：**
提供三種布局模式，其中默認布局使用無邊框設計風格，它並沒有強行填滿屏幕的每一個縫然後使用邊框線進行分隔，所有的功能版塊，都像是懸浮在屏幕上的，同時又將屏幕空間及其合理的利用了。

**🔐 權限驗證：**
可視化的管理權限，然後根據權限動態的註冊路由、菜單、頁面、按鈕（權限節點）、支持無限父子級權限分組、前後端搭配鑒權，自由分派頁面和按鈕權限。

**📝 未來可期：**
我們正在持續維護系統，並著手開發更多基礎設施模塊，按需一鍵安裝，甚至提供開箱即用的各行業完整應用。

**🧱 一舉多得：**
後台自適應PC、平板、手機等多種場景的支持，輕鬆應對各種需求。

**💖 其他雜項：**
角色組/管理員/管理員日誌、 會員/會員組/會員餘額、積分日誌、系統配置/控制台/附件管理/個人資料管理等等、更多特性等你探索...

### 安裝使用
💫 我們提供了完善的文檔，對於熟悉 `ThinkPHP` 和 `Vue` 的用戶，請使用大佬版：[快速上手](https://doc.buildadmin.com/guide/install/start.html) ，對於新人朋友，我們額外準備了各個操作系統的從零開始套餐：[Windows從零到一](https://doc.buildadmin.com/guide/install/windows.html) | [Linux從零到一](https://doc.buildadmin.com/guide/install/linux-bt.html) | [MacBook安裝引導](https://doc.buildadmin.com/guide/install/macBook.html)

### 聯繫我們
- [演示站](https://demo.buildadmin.com) 賬戶：`admin`，密碼：`123456`（演示站數據無法修改，請下載源碼安裝體驗全部功能）
- [問答社區：ask.buildadmin.com](https://ask.buildadmin.com)
- [官方網站：uni.buildadmin.com](https://uni.buildadmin.com)
- [文檔：doc.buildadmin.com](https://doc.buildadmin.com/)
- 加群：[687903819（已滿）](https://jq.qq.com/?_wv=1027&k=QwtXa14c)、[751852082](https://jq.qq.com/?_wv=1027&k=c8a7iSk8)
- [Gitee倉庫](https://gitee.com/wonderful-code/buildadmin)、[GitHub倉庫](https://github.com/build-admin/BuildAdmin)
- [官方郵箱 hi@buildadmin.com](mailto:hi@buildadmin.com)

### 項目預覽
|  |  |
|---------------------|---------------------|
|![登錄](https://doc.buildadmin.com/images/readme/login.gif)|![控制台](https://doc.buildadmin.com/images/readme/dashboard.png)|
|![布局配置](https://doc.buildadmin.com/images/readme/layout.png)|![表格](https://doc.buildadmin.com/images/readme/admin.png)|
|![表單](https://doc.buildadmin.com/images/readme/user.png)|![系統配置](https://doc.buildadmin.com/images/readme/config.png)|
|![數據回收規則](https://doc.buildadmin.com/images/readme/data-recycle.png)|![數據回收日誌](https://doc.buildadmin.com/images/readme/data-recycle-log.png)|
|![敏感數據](https://doc.buildadmin.com/images/readme/sensitive-data.png)|![菜單](https://doc.buildadmin.com/images/readme/menu.png)|
|![單欄布局](https://doc.buildadmin.com/images/readme/layout-3.png)|![經典布局](https://doc.buildadmin.com/images/readme/layout-2.png)|

### 特別鳴謝
💕 感謝巨人提供肩膀，排名不分先後
- [Thinkphp](http://www.thinkphp.cn/)
- [FastAdmin](https://gitee.com/karson/fastadmin)
- [Vue](https://github.com/vuejs/core)
- [vue-next-admin](https://gitee.com/lyt-top/vue-next-admin)
- [Element Plus](https://github.com/element-plus/element-plus)
- [TypeScript](https://github.com/microsoft/TypeScript)
- [vue-router](https://github.com/vuejs/vue-router-next)
- [vite](https://github.com/vitejs/vite)
- [Pinia](https://github.com/vuejs/pinia)
- [Axios](https://github.com/axios/axios)
- [nprogress](https://github.com/rstacruz/nprogress)
- [screenfull](https://github.com/sindresorhus/screenfull.js)
- [mitt](https://github.com/developit/mitt)
- [sass](https://github.com/sass/sass)
- [echarts](https://github.com/apache/echarts)
- [vueuse](https://github.com/vueuse/vueuse)
- [lodash](https://github.com/lodash/lodash)
- [eslint](https://github.com/eslint/eslint)
- [prettier](https://github.com/prettier/prettier)
- [Sortable](https://github.com/SortableJS/Sortable)
- [v-code-diff](https://github.com/Shimada666/v-code-diff)
- [clicaptcha](https://github.com/hooray/clicaptcha)
- [phinx](https://github.com/cakephp/phinx)
- [jetbrains](https://www.jetbrains.com/)

### 版權信息
🔐 BuildAdmin 遵循 `Apache2.0` 開源協議發布，提供無需授權的免費使用。\
本項目包含的第三方源碼和二進制文件之版權信息另行標註。

### 支持項目
💕 無需捐贈，如果覺得項目不錯，或者已經在使用了，希望你可以去 [Github](https://github.com/build-admin/BuildAdmin) 或者 [Gitee](https://gitee.com/wonderful-code/buildadmin) 幫我們點個 ⭐ Star，這將是對我們極大的鼓勵與支持。
