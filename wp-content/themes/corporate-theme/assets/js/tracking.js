/**
 * 追踪查询 AJAX
 *
 * 拦截追踪表单提交，通过 AJAX 异步查询
 * 不刷新页面，直接更新结果区域
 *
 * @package corporate-theme
 */

document.addEventListener('DOMContentLoaded', function () {

    var form = document.getElementById('freight-tracking-form');
    if (!form) {
        return; // 页面没有追踪表单时不做任何事
    }

    var input = document.getElementById('tracking_no_input');
    var resultDiv = document.getElementById('tracking-result');
    var submitBtn = document.getElementById('tracking-submit-btn');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // 阻止表单默认提交（不刷新页面）

        var trackingNo = input.value.trim();
        if (!trackingNo) {
            resultDiv.innerHTML = '<div class="alert alert-warning">请输入追踪编号。</div>';
            return;
        }

        // 显示加载状态
        submitBtn.disabled = true;
        submitBtn.textContent = '查询中...';
        resultDiv.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">加载中...</span></div></div>';

        // 发送 AJAX 请求
        var xhr = new XMLHttpRequest();
        xhr.open('POST', freight_ajax.ajax_url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        xhr.onload = function () {
            submitBtn.disabled = false;
            submitBtn.textContent = '查询';

            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        resultDiv.innerHTML = response.data.html;
                    } else {
                        resultDiv.innerHTML = response.data.html || '<div class="alert alert-danger">查询失败，请重试。</div>';
                    }
                } catch (e) {
                    resultDiv.innerHTML = '<div class="alert alert-danger">服务器返回数据异常，请刷新页面重试。</div>';
                }
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">网络错误，请检查网络后重试。</div>';
            }
        };

        xhr.onerror = function () {
            submitBtn.disabled = false;
            submitBtn.textContent = '查询';
            resultDiv.innerHTML = '<div class="alert alert-danger">网络错误，请检查网络后重试。</div>';
        };

        var params = 'action=freight_track&nonce=' + encodeURIComponent(freight_ajax.nonce)
                   + '&tracking_no=' + encodeURIComponent(trackingNo);
        xhr.send(params);
    });
});