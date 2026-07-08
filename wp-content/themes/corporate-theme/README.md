# Freight Forwarder Pro — WordPress 货代企业主题

> 专业货代公司官网 WordPress 主题，集成货运追踪系统（CPT + REST API）、WooCommerce 在线订舱、ACF 货运信息管理、邮件自动化通知等完整功能。基于 Bootstrap 5 构建，响应式设计，适配全球物流业务场景。

---

## 项目概述

Freight Forwarder Pro 是一套为国际货运代理公司量身定制的 WordPress 企业主题。区别于通用企业主题，本主题专注于**货代行业核心业务场景**，提供了从货物追踪、在线订舱到后端管理的完整数字化解决方案。

### 核心业务场景

| 场景 | 实现方案 | 技术亮点 |
|:----|---------|:--------:|
| 货物追踪查询 | 自定义 CPT `shipment` + 追踪编号系统 | `meta_query` 高级查询 |
| 在线订舱服务 | WooCommerce 商品 + 自定义属性 | 模板覆盖 + 钩子重排 |
| 物流状态 API | `GET /wp-json/freight/v1/track?tracking_no=XXX` | REST API 自定义端点 |
| 货运信息管理 | ACF 字段组（起运港/目的港/ETD/ETA） | `acf_add_local_field_group` 代码注册 |
| 邮件自动通知 | 订单来源标记 + 状态变化追踪 | 邮件模板覆盖 + `post_meta` |
| 运输方式分类 | 海运/空运/陆运标签系统 | 自定义分类法 |

---

## 环境要求

- PHP >= 7.4（推荐 8.0+）
- WordPress >= 6.0
- 必需插件：Advanced Custom Fields (ACF) Pro
- 推荐插件：WooCommerce（在线订舱功能，主题已覆盖完整模板覆盖）

---

## 安装

1. 将 `freight-forwarder-pro` 文件夹上传到 `/wp-content/themes/`
2. 在 WordPress 后台 → 外观 → 主题中启用
3. 安装并激活 ACF Pro 插件（货运信息字段自动加载）
4. 可选：安装 WooCommerce 插件启用在线订舱功能
5. 在"外观 → 主题设置"中配置首页 Hero 内容

---

## 功能架构

### 货运追踪系统

通过自定义文章类型 `shipment`（货运订单）实现完整的货物追踪功能：

- **自动追踪编号生成**：发布货运订单时自动生成 `FRE-YYYYMMDD-XXXX` 格式唯一编号
- **状态分类管理**：通过 `shipment_status` 分类法管理运输状态（待处理、已揽收、运输中、已到港、已签收等）
- **运输方式标签**：通过 `shipping_mode` 标签区分海运、空运、陆运
- **ACF 详细信息**：起运港、目的港、货物重量（kg）、体积（CBM）、ETD、ETA 等字段

### REST API 追踪接口

```
GET /wp-json/freight/v1/track?tracking_no=FRE-20260708-1234
```

响应示例：

```json
{
  "success": true,
  "tracking_no": "FRE-20260708-1234",
  "status": "运输中",
  "mode": "海运",
  "origin": "上海港",
  "destination": "汉堡港",
  "weight": "1500",
  "volume": "8.5",
  "etd": "2026-07-10",
  "eta": "2026-08-05",
  "last_update": "2026-07-08 14:30:00",
  "permalink": "https://example.com/shipment/fre-20260708-1234"
}
```

### 前端追踪查询短代码

在任意页面/文章中使用 `[tracking_form]` 短代码，即可显示货物追踪查询表单，用户输入追踪编号后实时查询并展示详细货运信息。

### WooCommerce 在线订舱

- 将货运服务作为商品管理，支持在线下单
- 商品详情页展示货运服务说明（运输方式、服务范围、报关服务等）
- 自定义结算字段适配货运业务场景
- 运费规则定制（满额免运费、COD 手续费等）

### 邮件通知系统

- 管理员新订单邮件显示订单来源（来自网站/来自 App 端）
- 客户订单完成通知模板
- 订单状态变化自动记录日志

---

## 文件结构

```
freight-forwarder-pro/
├── style.css                        # 主题样式入口
├── index.php                        # 主模板（兜底）
├── functions.php                    # 核心功能（CPT / REST API / ACF / 筛选 / 货运追踪）
├── header.php / footer.php          # 页眉/页脚
├── front-page.php                   # 首页模板（Hero + 货运服务 + 追踪查询 + 在线订舱）
├── single.php / single-portfolio.php
├── page.php / page-about.php / page-full-width.php
├── archive.php / archive-portfolio.php
├── search.php / 404.php
├── sidebar.php
├── assets/
│   └── css/
│       └── custom.css               # 自定义样式
├── template-parts/
│   └── content-pagination.php
├── woocommerce.php                  # WooCommerce 兜底模板
├── woocommerce/
│   ├── content-product.php          # 商品列表卡片
│   ├── single-product.php           # 商品详情页
│   ├── single-product/
│   │   └── sale-flash.php           # 促销标签
│   ├── loop/
│   │   └── sale-flash.php
│   └── emails/
│       ├── email-header.php
│       ├── admin-new-order.php      # 管理员新订单通知（含订单来源）
│       └── customer-completed-order.php
└── screenshots/                     # 功能截图
```

---

## 核心技术点

### 自定义文章类型（CPT）

| 类型 | Slug | 用途 |
|:----|:----|:----|
| Shipment | `shipment` | 货运订单追踪 |
| Portfolio | `portfolio` | 客户案例展示 |
| Team | `team` | 团队成员管理 |

### 自定义分类法

| 分类法 | 关联 CPT | 类型 | 用途 |
|:------|:--------|:----|:----|
| `shipment_status` | shipment | 层级 | 货运状态管理 |
| `shipping_mode` | shipment | 标签 | 运输方式分类 |
| `portfolio_type` | portfolio | 层级 | 案例类型 |
| `portfolio_tag` | portfolio | 标签 | 案例标签 |
| `department` | team | 层级 | 部门管理 |
| `team_tag` | team | 标签 | 技能标签 |

### REST API 端点

| 端点 | 方法 | 用途 |
|:----|:----|:----|
| `/myapp/v1/top-products` | GET | 销量前 5 商品 |
| `/freight/v1/track` | GET | 货运追踪查询 |

### ACF 字段组

| 字段组 | 关联 | 核心字段 |
|:------|:----|:---------|
| 货运详细信息 | shipment | 追踪编号、起运港、目的港、重量、体积、ETD、ETA |
| 客户案例详情 | case | 客户 Logo、简介、所属行业 |
| 产品手册下载 | product | PDF 文件上传 |
| 主题设置 | 选项页 | Hero 标题、CTA 按钮 |

### 钩子使用

- **动作钩子**：`woocommerce_single_product_summary`、`woocommerce_before_cart`、`woocommerce_checkout_order_processed`、`save_post_shipment`、`transition_post_status` 等
- **过滤器钩子**：`the_content`、`woocommerce_checkout_fields`、`woocommerce_package_rates`、`woocommerce_product_get_price`、`pre_get_posts` 等
- **自定义钩子**：`corporate_after_header`、`corporate_before_footer` 等

---

## SEO 优化

本主题在代码层面实现了完整的 SEO 优化方案，不依赖任何第三方 SEO 插件：

### 结构化数据（Schema.org JSON-LD）

通过 `wp_head` 钩子输出 JSON-LD 结构化数据，在不同页面类型输出对应 Schema：

| Schema 类型 | 输出页面 | 作用 |
|:-----------|:--------|:----|
| `Organization` | 首页 | 告诉 Google 公司名称、服务范围（海运/空运/陆运/报关） |
| `WebSite` | 首页 | 激活搜索结果的 Sitelinks Search Box |
| `Article` | 文章详情页 | 搜索结果展示文章作者、发布时间、封面图 |
| `BreadcrumbList` | 面包屑导航 | 配合面包屑输出，展示页面层级路径 |

### Open Graph / Twitter Card

手动实现 OG 标签，覆盖所有页面类型：
- `og:title`、`og:description`、`og:image`、`og:url` 等核心标签
- 文章/页面优先使用摘要和特色图片
- 分类/标签页自动获取分类名称和描述
- Twitter Card 使用 `summary_large_image` 卡片样式

### Meta Description 自动生成

为每个页面类型自动生成合适的 meta description：

- **文章/页面**：优先用文章摘要，没有摘要则截取正文前 30 字
- **首页**：用 WordPress 站点描述
- **分类页**：用分类描述，无描述则自动拼接"分类名 — 相关货运物流资讯"
- **货运追踪归档页**：固定输出"实时查询货物运输状态..."
- **搜索结果页**：输出"搜索'关键词'的相关货运物流内容"

### Canonical URL

所有页面输出 canonical 标签，防止重复内容问题：
- 商品页可能通过加购参数访问（`?add-to-cart=`），canonical 指向原始 URL
- 排序/筛选参数不会导致重复内容被搜索引擎降权

### 性能优化

- 移除不必要的 `wp_head` 输出（版本号、RSD、WLW 等）
- Bootstrap JS 添加 `defer` 属性，不阻塞页面渲染
- 提升 Google Core Web Vitals 评分

### 面包屑导航

自定义面包屑函数 `freight_breadcrumb()`，在 `header.php` 中调用：
- 首页不显示，避免重复内容
- 支持文章、页面、自定义文章类型、分类、搜索、404 等所有页面类型
- 语义化 HTML 结构（`<nav aria-label="breadcrumb">`）
- 配合 BreadcrumbList Schema 结构化数据

---

## 技术亮点

###  4 个核心功能

1. **货物追踪系统** → 后台添加货运订单，前台用 `[tracking_form]` 查询
2. **REST API 追踪接口** → 浏览器访问 `/wp-json/freight/v1/track?tracking_no=xxx` 返回 JSON
3. **在线订舱流程** → 通过 WooCommerce 完成货运服务下单全流程
4. **邮件自动通知** → 新订单邮件自动显示订单来源 + 货运状态变更通知

### 技术能力证明

- ✅ WordPress 模板层级 + 模板覆盖
- ✅ 自定义文章类型 + 分类法完整实现
- ✅ REST API 自定义端点开发
- ✅ ACF 高级自定义字段深度集成
- ✅ WooCommerce 全流程定制（钩子/模板/邮件）
- ✅ WP_Query + meta_query 高级查询
- ✅ 安全转义规范（esc_* / wp_kses_post）
- ✅ WP Cron 定时任务 + 外部 API 请求
- ✅ 数据迁移脚本 + 批量处理
- ✅ SEO 结构化数据（Schema.org JSON-LD）
- ✅ Open Graph / Twitter Card 社交标签
- ✅ 面包屑导航 + 语义化 HTML
- ✅ Canonical URL 防止重复内容
- ✅ Core Web Vitals 性能优化

---

## GitHub

仓库地址：https://github.com/nicewangli/WPPROJECT

## 开发者

- 项目：Freight Forwarder Pro — WordPress 货代企业主题
- 版本：1.0.0
- 日期：2026-07-07