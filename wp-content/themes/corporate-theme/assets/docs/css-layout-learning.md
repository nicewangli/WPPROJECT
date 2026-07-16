# CSS 布局实战复盘：侧边栏卡片溢出问题的教训

> 从一次 WooCommerce 侧边栏卡片"挤在一起"的 bug，揭示现代 CSS 布局中的常见陷阱和正确思路。

---

## 一、布局模式的本质差异

这是本次 bug 的根源。CSS 有三种主流布局模式，它们的子元素溢出和换行行为截然不同。

| 布局模式 | 控制溢出的方式 | 子项宽度参考系 |
|----------|---------------|----------------|
| `float` | `clear` / `overflow: hidden` | 父容器包含块宽度 |
| `flex` | `flex-wrap` / `flex-basis` | 包含块宽度（默认），或 flex-basis |
| `grid` | `grid-template-columns` / `auto-fill` | grid track 尺寸 |

### 踩坑记录：选错清除手段

**错误做法**——对 Cart Block（flex 布局）使用 `float: none` 和 `clear: both`：

```css
/* 这是 float 布局的清除方式，对 flex 容器无效 */
.cart-collaterals {
    clear: both;           /* flex 容器无视 clear */
    overflow: hidden;      /* 只在 float 场景有效 */
}
```

**正确做法**——针对 flex 容器，强制子项换行并占满宽度：

```css
.wc-block-cart {
    flex-wrap: wrap !important;
}
.wc-block-cart > * {
    flex: 0 0 100% !important;
    max-width: 100% !important;
}
```

**快速判断方法**：在 DevTools Elements 面板选中元素，看 Computed 面板中 `display` 的值——`flex` 就找 `flex-wrap`，`block` + `float` 就找 `clear`。

---

## 二、百分比宽度的"相对对象"陷阱

`width: 100%` 相对于**包含块（containing block）**的宽度。

```html
<div class="row">
  <aside class="col-md-3">    <!-- 包含块宽度 = 行宽的 25% -->
    <div class="widget card">   <!-- width: 100% = 行宽的 25%  -->
```

侧边栏 `col-md-3`（25%）下的卡片，`width: 100%` 只有整行宽度的四分之一，容纳较多内容自然会挤。

**解决思路**：

- 加宽侧边栏列：`col-md-3`（25%）→ `col-md-4`（33%）
- 或让卡片溢出侧边栏：`width: 200%` + `overflow: visible` + `position: relative` + `z-index`

| 方案 | 卡片实际宽度 | 效果 |
|------|-------------|------|
| `col-md-3` + `width: 100%` | 行宽的 25% | 很挤 ❌ |
| `col-md-4` + `width: 100%` | 行宽的 33% | 还行 ✅ |
| `col-md-4` + `width: 200%`（桌面端） | 行宽的 66% | 宽裕 ✅✅ |

**教训**：百分比宽度不是抽象的"100%"，它有具体的物理意义。理解这一点才能判断是加宽列还是溢出。

---

## 三、Bootstrap 列在手机端的塌陷行为

很多 Bootstrap 用户的盲区：

```
col-md-4 在 >= 768px 时占 33% 宽度
col-md-4 在 <  768px 时退化为 100% 宽度（列塌陷）
```

这意味着：
- **桌面端** `width: 200%` 允许卡片向右溢出进入主内容区，效果很好
- **手机端** 列塌陷为 `100%` 后，`width: 200%` = 2 倍屏幕宽 → 溢出到屏幕右侧

**解决方案**：用 `@media (min-width: 992px)` 包裹桌面端溢出规则，手机端只保留 `width: 100%`。

```css
.widget.card {
    width: 100%;       /* 手机端默认 */
}

@media (min-width: 992px) {
    #secondary .widget.card {
        width: 200%;   /* 桌面端溢出 */
        position: relative;
        z-index: 2;
    }
    #secondary {
        overflow: visible;
    }
}
```

---

## 四、CSS 选择器的作用域策略

### 问题

最开始的选择器限定在 `.woocommerce`，导致：

| 页面类型 | 是否带 `.woocommerce` 类 | 是否命中规则 | 结果 |
|---------|------------------------|-------------|------|
| WooCommerce 页面 | 是 | 命中 | 生效 ✅ |
| 普通 Page | 否 | 不命中 | 不生效 ❌ |
| Archive / Search | 否 | 不命中 | 不生效 ❌ |
| Single Post | 无侧边栏 | — | 不需要 |

### 正确做法

选择器应该反映**页面结构的相同点**，而不是"当前在哪类页面上"。所有带侧边栏的模板都用同一个 `id="secondary"`：

```php
// sidebar.php、woocommerce.php 都用相同的 id
<aside id="secondary" class="...">
```

所以选择器应该是：

```css
/* 好：基于稳定的 DOM 结构，覆盖所有页面 */
#secondary .widget.card { ... }

/* 不好：基于页面类型假设，会漏掉非 WooCommerce 页面 */
.woocommerce .widget.card { ... }
```

### 选择策略指南

| 场景 | 推荐选择器 | 理由 |
|------|-----------|------|
| 某个组件在所有页面表现一致 | 用 `id` 或通用 class | `.woocommerce` 会漏掉其他页面 |
| 某个样式只在某类页面生效 | 再用父类限定 | 如只在 WooCommerce 隐藏某元素 |
| 某个样式依赖页面类型不同的 DOM 结构 | 分开写规则 | 结构不同无法共用 |

---

## 五、overflow 的级联效应

Bootstrap 的 `.col-*` 没有 `overflow: hidden`，但 `.card`、`.table-responsive` 等组件可能有。

设置 `#secondary { overflow: visible }` 只是允许侧边栏自身的溢出，**还需要检查从子元素到根节点之间是否有任何元素裁剪了溢出**。

```css
#secondary {
    overflow: visible !important;
}
```

**关键原则**：要允许子元素溢出，需要从子元素到最近的有 `overflow` 限制（非 visible）的祖先之间的所有元素都允许溢出，缺一环就失效。

---

## 六、一次完整的排查流程

这次实战可以抽象成一个通用排查方法：

1. **选中最窄的元素**，看它的 `width` 和父容器宽度
2. **在 Styles 面板直接改 `width: 200%`**，看效果是否改善
3. **用 Computed 面板确认实际像素值**，理解百分比对应的物理宽度
4. **切换响应式模式**，逐个断点检查（手机 / 平板 / 桌面）
5. **检查层叠上下文**——有没有 `overflow: hidden` 的祖先把溢出裁了
6. **检查布局模式**——元素在 `flex` 还是 `block` 容器里？
7. **检查选择器范围**——如果样式只在部分页面生效，看看选择器是否排除了其他页面

---

## 七、核心收获

```
布局问题排查清单：

1. 这个元素在什么布局模式里？  → block / flex / grid / float
2. 父容器宽度是多少？          → Bootstrap 列占比？
3. 手机端会塌缩成 100% 吗？     → @media 断点
4. 溢出有没有被 overflow: hidden 裁掉？
5. 选择器会不会漏掉其他页面？   → id 还是页面类？
6. width: 100% 的"100%"实际指什么物理宽度？
```

**一句话总结**：现代 CSS 布局的 bug 几乎总是"选错了布局模式的清除方式"或者"选错了选择器的作用域"，理解这两点能解决 80% 的布局问题。
