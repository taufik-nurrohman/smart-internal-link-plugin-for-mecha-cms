(function(w, d, base) {
    if (typeof base.composer === "undefined") return;
    var speak = base.languages,
        title = speak.plugin_smart_internal_link_title;
    base.composer.button('book plugin-smart-internal-link', {
        title: title,
        click: function(e, editor) {
            editor.modal('smart-internal-link', function(overlay, modal) {
                var s = editor.grip.selection(),
                    ok = d.createElement('button'),
                    cancel = d.createElement('button'),
                    input = d.createElement('input'),
                    select = d.createElement('select'),
                    types = speak.plugin_smart_internal_link_title_types.split(','),
                    types_o = ['article', 'page'];
                for (var i in types) {
                    select.innerHTML += '<option value="' + types_o[i] + '"' + (types_o[i] === base.segment ? ' selected' : "") + '>' + types[i] + '</option>';
                }
                input.type = 'text';
                input.placeholder = title.toLowerCase().replace(/[^a-z0-9\-]+/g, '-').replace(/^-+|-+$/, "");
                modal.children[0].innerHTML = title;
                modal.children[1].appendChild(input);
                modal.children[1].appendChild(select);
                ok.innerHTML = editor.grip.config.buttons.ok;
                cancel.innerHTML = editor.grip.config.buttons.cancel;
                var insert = function() {
                    if (!input.value.length) return false;
                    var str = '{{' + select.value + '.link:' + input.value.replace(/<.*?>|&(?:[a-z0-9]+|#[0-9]+|#x[a-f0-9]+);/gi, ' ').replace(/[^a-z0-9\-]+/gi, '-').replace(/\-+/g, '-').replace(/^\-|\-$/g, "").toLowerCase() + '}}';
                    if (s.value.length) {
                        editor.grip.wrap(str, '{{/' + select.value + '}}');
                    } else {
                        editor.grip.insert(str);
                    }
                    return false;
                };
                editor.event("keydown", input, function(e) {
                    if (e.keyCode === 13) return insert();
                    if (e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 40) return ok.focus(), false;
                });
                editor.event("click", ok, insert);
                editor.event("click", cancel, function() {
                    return editor.close(true);
                });
                editor.event("keydown", ok, function(e) {
                    if (e.keyCode === 13) return insert();
                    if (e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 38) return input.focus(), false;
                    if (e.keyCode === 39 || e.keyCode === 40) return cancel.focus(), false;
                });
                editor.event("keydown", cancel, function(e) {
                    if (e.keyCode === 13 || e.keyCode === 27) return editor.close(true), false;
                    if (e.keyCode === 37 || e.keyCode === 38) return ok.focus(), false;
                    if (e.keyCode === 40) return false;
                });
                modal.children[2].appendChild(ok);
                modal.children[2].appendChild(cancel);
                w.setTimeout(function() {
                    input.focus();
                    input.select();
                }, 10);
            });
        }
    });
    // MTE & HTE >= 1.4.0
    if (typeof base.composer.shortcut === "function") {
        base.composer.shortcut('CTRL+SHIFT+76', function() {
            return base.composer.grip.config.buttons['book plugin-smart-internal-link'].click(false, base.composer), false;
        });
    }
})(window, document, DASHBOARD);