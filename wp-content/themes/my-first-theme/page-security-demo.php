<?php
/**
 * Template Name:安全转义练习页
 * Description：D20 mvp 对比危险写法与安全写法
 */
get_header();

$submitted_name    = '';
$submitted_link    = '';
$submitted_html    = '';
$stored_name       = '';
$stored_link       = '';
$stored_html       = '';

if ( isset( $_POST['demo_submit'] ) ) {

    $submitted_name = $_POST['user_name'];
    $submitted_link = $_POST['user_link'];
    $submitted_html = $_POST['user_html'];

    $clean_name = sanitize_text_field( $_POST['user_name'] );
    $clean_link = esc_url_raw( $_POST['user_link'] );
    $clean_html = wp_kses_post( $_POST['user_html'] );

    update_option( 'demo_user_name', $clean_name );
    update_option( 'demo_user_link', $clean_link );
    update_option( 'demo_user_html', $clean_html );

    $stored_name = get_option( 'demo_user_name' );
    $stored_link = get_option( 'demo_user_link' );
    $stored_html = get_option( 'demo_user_html' );
}
?>
<main class="security-demo">
    <h1>D20 安全转义练习</h1>

    <section class="demo-form">
        <h2>📝 模拟用户输入（请尝试输入攻击代码）</h2>
        <form method="post" action="">
            <p>
                <label>用户名（试试输入 &lt;script&gt;alert(1)&lt;/script&gt;）：</label><br>
                <input type="text" name="user_name" value="">
            </p>
            <p>
                <label>个人链接（试试输入 javascript:alert(1)）：</label><br>
                <input type="text" name="user_link" value="">
            </p>
            <p>
                <label>自我介绍（试试输入有 &lt;strong&gt;加粗&lt;/strong&gt; 和 &lt;script&gt;的混合内容）：</label><br>
                <textarea name="user_html" rows="4" cols="50"></textarea>
            </p>
            <p>
                <button type="submit" name="demo_submit" value="1">提交测试</button>
            </p>
        </form>
    </section>
    <?php if ( $submitted_name ) : ?>
    <section class="demo-result">
        <h2>场景1：esc_html() —— 标签之间的纯文字</h2>

        <h3>原始输入（你提交的脏数据）：</h3>
        <code><?php echo $submitted_name; ?></code>

        <h3>❌ 危险写法（直接 echo）：</h3>
        <div class="box danger">
            <?php echo $stored_name; ?>
        </div>

        <h3>✅ 安全写法（esc_html 转义）：</h3>
        <div class="box safe">
            <?php echo esc_html( $stored_name ); ?>
        </div>
    </section>
    <?php endif; ?>
    <?php if ( $submitted_name ) : ?>
    <section class="demo-result">
        <h2>场景2：esc_attr() —— 属性值里的文字</h2>

        <h3>❌ 危险写法（直接拼入属性）：</h3>
        <div class="box danger">
            <input type="text" value="<?php echo $stored_name; ?>">
        </div>

        <h3>✅ 安全写法（esc_attr 转义）：</h3>
        <div class="box safe">
            <input type="text" value="<?php echo esc_attr( $stored_name ); ?>">
        </div>
    </section>
    <?php endif; ?>
    <?php if ( $submitted_link ) : ?>
    <section class="demo-result">
        <h2>场景3：esc_url() —— 链接地址</h2>

        <h3>❌ 危险写法（直接拼入 href）：</h3>
        <div class="box danger">
            <a href="<?php echo $stored_link; ?>">点击这个链接</a>
        </div>

        <h3>✅ 安全写法（esc_url 转义）：</h3>
        <div class="box safe">
            <a href="<?php echo esc_url( $stored_link ); ?>">点击这个链接</a>
        </div>
    </section>
    <?php endif; ?>
     <?php if ( $submitted_html ) : ?>
    <section class="demo-result">
        <h2>场景4：wp_kses() —— 允许部分 HTML</h2>

        <h3>原始输入（你提交的脏数据，含标签）：</h3>
        <code><?php echo $submitted_html; ?></code>

        <h3>❌ 危险写法（直接 echo raw 数据）：</h3>
        <div class="box danger">
            <?php echo $stored_html; ?>
        </div>

        <h3>✅ 安全写法（wp_kses_post 过滤后输出）：</h3>
        <div class="box safe">
            <?php echo wp_kses_post( $stored_html ); ?>
        </div>
    </section>
    <?php endif; ?>
    <?php if ( $submitted_html ) : ?>
    <section class="demo-result">
        <h2>场景5：the_field() 与 get_field() 的差异</h2>
        <p class="note">💡 ACF 的 <code>the_field()</code> 自带转义，<code>get_field()</code> 返回原始值！</p>

        <h3>安全 — the_field()：</h3>
        <div class="box safe">
            <?php the_field( 'my_demo_field', 'option' ); ?>
        </div>

        <h3>不一定安全 — echo get_field()：</h3>
        <div class="box danger">
            <?php echo get_field( 'my_demo_field', 'option' ); ?>
        </div>

        <h3>✅ 正确做法 — echo esc_html( get_field() )：</h3>
        <div class="box safe">
            <?php echo esc_html( get_field( 'my_demo_field', 'option' ) ); ?>
        </div>
    </section>
    <?php endif; ?>