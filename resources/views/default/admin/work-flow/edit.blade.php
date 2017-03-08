<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rappid Demo Application</title>
    <link rel="stylesheet" type="text/css" href="/assets/plugins/rappid/dist/rappid.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/admin/workflow/css/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/admin/workflow/css/theme-picker.css">
</head>
<body>

<div id="app">
    <div class="app-header">
        <div class="app-title">
            <h1>Rappid</h1>
        </div>
        <div class="toolbar-container"></div>
    </div>
    <div class="app-body">
        <div class="stencil-container"></div>
        <div class="paper-container"></div>
        <div class="inspector-container"></div>
        <div class="navigator-container"></div>
    </div>
</div>

<!-- Rappid/JointJS dependencies: -->
<script src="/assets/plugins/rappid/node_modules/jquery/dist/jquery.js"></script>
<script src="/assets/plugins/rappid/node_modules/lodash/index.js"></script>
<script src="/assets/plugins/rappid/node_modules/backbone/backbone.js"></script>
<script src="/assets/plugins/rappid/node_modules/graphlib/dist/graphlib.core.js"></script>
<script src="/assets/plugins/rappid/node_modules/dagre/dist/dagre.core.js"></script>

<!-- <script src="/assets/plugins/rappid/build/rappid.min.js"></script> -->
<script src="/assets/plugins/rappid/dist/rappid.js"></script>

<!--[if IE 9]>
<script>
    // `-ms-user-select: none` doesn't work in IE9
    document.onselectstart = function() { return false; };
</script>
<![endif]-->

<!-- Application files:  -->
<script src="/assets/admin/workflow/js/config/halo.js"></script>
<script src="/assets/admin/workflow/js/config/selection.js"></script>
<script src="/assets/admin/workflow/js/config/inspector.js"></script>
<script src="/assets/admin/workflow/js/config/stencil.js"></script>
<script src="/assets/admin/workflow/js/config/toolbar.js"></script>
<script src="/assets/admin/workflow/js/config/sample-graphs.js"></script>
<script src="/assets/admin/workflow/js/views/main.js"></script>
<script src="/assets/admin/workflow/js/views/theme-picker.js"></script>
<script src="/assets/admin/workflow/js/models/joint.shapes.app.js"></script>
<script>
    joint.setTheme('modern');
    app = new App.MainView({ el: '#app' });
    themePicker = new App.ThemePicker({ mainView: app });
    themePicker.render().$el.appendTo(document.body);
    app.graph.fromJSON(JSON.parse(App.config.sampleGraphs.emergencyProcedure));
</script>

<!-- Local file warning: -->
<div id="message-fs" style="display: none;">
    <p>The application was open locally using the file protocol. It is recommended to access it trough a <b>Web server</b>.</p>
    <p>Please see <a href="README.md">instructions</a>.</p>
</div>
<script>
    (function() {
        var fs = (document.location.protocol === 'file:');
        var ff = (navigator.userAgent.toLowerCase().indexOf('firefox') !== -1);
        if (fs && !ff) {
            (new joint.ui.Dialog({
                width: 300,
                type: 'alert',
                title: 'Local File',
                content: $('#message-fs').show()
            })).open();
        }
    })();
</script>

</body>
</html>
