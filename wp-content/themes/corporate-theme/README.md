# Corporate Theme - 企业主题

一个面向企业官网的 WordPress 主题，基于 Bootstrap 5 构建，响应式布局，支持 ACF 高级自定义字段。

## 环境要求

- PHP >= 7.4（推荐 8.0+）
- WordPress >= 6.0
- 必需插件：Advanced Custom Fields (ACF) Pro

## 安装

1. 将 `corporate-theme` 文件夹上传到 `/wp-content/themes/`
2. 在 WordPress 后台 → 外观 → 主题中启用
3. 导入 ACF 字段组 JSON 文件（如有）
4. 在"外观 → 主题设置"中配置 Hero 区域内容

## 功能清单

- [x] 响应式布局（Bootstrap 5 框架）
- [x] 自定义 Logo
- [x] 主菜单 + 底部菜单
- [x] 侧边栏 + 页脚小工具区
- [x] ACF 选项页（主题设置）
- [x] 作品集自定义文章类型（Portfolio CPT）
- [x] 作品类型分类法 + 作品标签
- [x] 首页模板（Hero + 服务卡片 + 最新文章 + 作品集）
- [x] 文章阅读时间估算
- [x] 文章版权声明
- [x] [cta] 短代码
- [x] 公司联系信息小工具
- [x] 主题定制器（颜色 / Hero 文字 / 版权信息）
- [x] 归档 / 搜索 / 404 页面模板
- [x] 全站输出安全转义

## 文件结构

```
corporate-theme/
├── style.css
├── index.php
├── functions.php
├── header.php
├── footer.php
├── front-page.php          # 首页模板
├── page.php                # 页面模板
├── page-about.php          # 关于我们页面
├── page-full-width.php     # 全宽页面模板
├── single.php              # 文章详情
├── single-portfolio.php    # 作品详情
├── content-single.php      # 文章内容部件
├── archive.php             # 归档
├── archive-portfolio.php   # 作品集归档
├── search.php              # 搜索结果
├── 404.php                 # 404页面
├── sidebar.php             # 侧边栏
├── assets/
│   └── css/
│       └── custom.css      # 自定义样式
└── template-parts/
    └── content-pagination.php
```

## 开发者

- 项目：WordPress 企业主题开发
- 日期：2026-06-10