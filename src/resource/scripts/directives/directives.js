

angular
        .module('6connect')
        .directive('pageTitle', pageTitle)
        .directive('sideNavigation', sideNavigation)
        .directive('minimalizaMenu', minimalizaMenu)
        .directive('sparkline', sparkline)
        .directive('icheck', icheck)
        .directive('panelTools', panelTools)
        .directive('smallHeader', smallHeader)
        .directive('animatePanel', animatePanel)
        .directive('landingScrollspy', landingScrollspy)
        .directive('paging', paging)
        .directive('dropzone', dropzone)
        .directive('selectOnClick', ['$window', function ($window) {
                  // Linker function
                  return function (scope, element, attrs) {
                       element.bind('click', function () {
                            if (!$window.getSelection().toString()) {
                                 this.setSelectionRange(0, this.value.length)
                            }
                       });
                  };
             }]);

function dropzone() {
     return {
          restrict: 'E',
          link: function (scope, element, attrs) {

               var config = scope.dropezone_config;
               var eventHandlers = {
                    'addedfile': function (file) {
                         scope.file = file;
                    },
                    'success': function (file, response) {
                         var result = JSON.parse(response);
                         if (result.files) {
                              file.uploadedpath = result.files;
                              scope.uploads.push(result.files);
                         }
                    },
                    'error': function (response) {
                    },
                    'removedfile': function (file) {
                         if (file.uploadedpath != undefined) {
                              $.post(scope.remove_url, {"file": file.uploadedpath.file_name, 'req_id': scope.edit_request_id});
                              var index = scope.uploads.indexOf(file);
                              scope.uploads.splice(index, 1);
                         }
                    }

               };

               dropzone = new Dropzone(element[0], config);


               scope.mock = function () {
                    angular.forEach(scope.uploads, function (upload, key) {
                         console.log(key + ': ' + upload);
                         var fpath = upload.path;
                         if (fpath != ROOT_PATH && fpath != null) {
                              var mockFile = {name: upload.name, size: upload.filesize, filepath: fpath, uploadedpath: {'file_name': upload.file_name}};
                              dropzone.options.addedfile.call(dropzone, mockFile);
                              dropzone.emit("complete", mockFile);
                              dropzone.files.push(mockFile);
                         }
                    });
               }
               angular.forEach(eventHandlers, function (handler, event) {
                    dropzone.on(event, handler);
               });
               scope.processDropzone = function () {
                    dropzone.processQueue();
               };
               scope.resetDropzone = function () {
                    dropzone.removeAllFiles();
               }

          }
     }
}
/**
 * @ngDoc directive
 * @name ng.directive:paging
 *
 * @description
 * A directive to aid in paging large datasets
 * while requiring a small amount of page
 * information.
 *
 * @element EA
 *
 */
function paging() {

     // Assign null-able scope values from settings
     function setScopeValues(scope, attrs) {

          scope.List = [];
          scope.Hide = false;
          scope.page = parseInt(scope.page) || 1;
          scope.total = parseInt(scope.total) || 0;
          scope.dots = scope.dots || '...';
          scope.ulClass = scope.ulClass || 'pagination';
          scope.adjacent = parseInt(scope.adjacent) || 2;
          scope.activeClass = scope.activeClass || 'active';
          scope.disabledClass = scope.disabledClass || 'disabled';

          scope.scrollTop = scope.$eval(attrs.scrollTop);
          scope.hideIfEmpty = scope.$eval(attrs.hideIfEmpty);
          scope.showPrevNext = scope.$eval(attrs.showPrevNext);

     }


     // Validate and clean up any scope values
     // This happens after we have set the
     // scope values
     function validateScopeValues(scope, pageCount) {

          // Block where the page is larger than the pageCount
          if (scope.page > pageCount) {
               scope.page = pageCount;
          }

          // Block where the page is less than 0
          if (scope.page <= 0) {
               scope.page = 1;
          }

          // Block where adjacent value is 0 or below
          if (scope.adjacent <= 0) {
               scope.adjacent = 2;
          }

          // Hide from page if we have 1 or less pages
          // if directed to hide empty
          if (pageCount <= 1) {
               scope.Hide = scope.hideIfEmpty;
          }
     }


     // Internal Paging Click Action
     function internalAction(scope, page) {

          // Block clicks we try to load the active page
          if (scope.page == page) {
               return;
          }

          // Update the page in scope 
          scope.page = page;

          // Pass our parameters to the paging action
          scope.pagingAction({
               page: scope.page,
               pageSize: scope.pageSize,
               total: scope.total
          });

          // If allowed scroll up to the top of the page
          if (scope.scrollTop) {
               scrollTo(0, 0);
          }
     }


     // Adds the first, previous text if desired   
     function addPrev(scope, pageCount) {

          // Ignore if we are not showing
          // or there are no pages to display
          if (!scope.showPrevNext || pageCount < 1) {
               return;
          }

          // Calculate the previous page and if the click actions are allowed
          // blocking and disabling where page <= 0
          var disabled = scope.page - 1 <= 0;
          var prevPage = scope.page - 1 <= 0 ? 1 : scope.page - 1;

          var first = {
               value: 'First',
               title: 'First Page',
               liClass: disabled ? scope.disabledClass : '',
               action: function () {
                    if (!disabled) {
                         internalAction(scope, 1);
                    }
               }
          };

          var prev = {
               value: 'Previous',
               title: 'Previous Page',
               liClass: disabled ? scope.disabledClass : '',
               action: function () {
                    if (!disabled) {
                         internalAction(scope, prevPage);
                    }
               }
          };

          scope.List.push(first);
          scope.List.push(prev);
     }


     // Adds the next, last text if desired
     function addNext(scope, pageCount) {

          // Ignore if we are not showing 
          // or there are no pages to display
          if (!scope.showPrevNext || pageCount < 1) {
               return;
          }

          // Calculate the next page number and if the click actions are allowed
          // blocking where page is >= pageCount
          var disabled = scope.page + 1 > pageCount;
          var nextPage = scope.page + 1 >= pageCount ? pageCount : scope.page + 1;

          var last = {
               value: 'Last',
               title: 'Last Page',
               liClass: disabled ? scope.disabledClass : '',
               action: function () {
                    if (!disabled) {
                         internalAction(scope, pageCount);
                    }
               }
          };

          var next = {
               value: 'Next',
               title: 'Next Page',
               liClass: disabled ? scope.disabledClass : '',
               action: function () {
                    if (!disabled) {
                         internalAction(scope, nextPage);
                    }
               }
          };

          scope.List.push(next);
          scope.List.push(last);
     }


     // Add Range of Numbers
     function addRange(start, finish, scope) {

          var i = 0;
          for (i = start; i <= finish; i++) {

               var item = {
                    value: i,
                    title: 'Page ' + i,
                    liClass: scope.page == i ? scope.activeClass : '',
                    action: function () {
                         internalAction(scope, this.value);
                    }
               };

               scope.List.push(item);
          }
     }


     // Add Dots ie: 1 2 [...] 10 11 12 [...] 56 57
     function addDots(scope) {
          scope.List.push({
               value: scope.dots
          });
     }


     // Add First Pages
     function addFirst(scope, next) {
          addRange(1, 2, scope);

          // We ignore dots if the next value is 3
          // ie: 1 2 [...] 3 4 5 becomes just 1 2 3 4 5 
          if (next != 3) {
               addDots(scope);
          }
     }


     // Add Last Pages
     function addLast(pageCount, scope, prev) {

          // We ignore dots if the previous value is one less that our start range
          // ie: 1 2 3 4 [...] 5 6  becomes just 1 2 3 4 5 6 
          if (prev != pageCount - 2) {
               addDots(scope);
          }

          addRange(pageCount - 1, pageCount, scope);
     }



     // Main build function
     function build(scope, attrs) {

          // Block divide by 0 and empty page size
          if (!scope.pageSize || scope.pageSize < 0) {
               return;
          }

          // Assign scope values
          setScopeValues(scope, attrs);

          // local variables
          var start,
                  size = scope.adjacent * 2,
                  pageCount = Math.ceil(scope.total / scope.pageSize);

          // Validate Scope
          validateScopeValues(scope, pageCount);

          // Calculate Counts and display
          addPrev(scope, pageCount);
          if (pageCount < (5 + size)) {

               start = 1;
               addRange(start, pageCount, scope);

          } else {

               var finish;

               if (scope.page <= (1 + size)) {

                    start = 1;
                    finish = 2 + size + (scope.adjacent - 1);

                    addRange(start, finish, scope);
                    addLast(pageCount, scope, finish);

               } else if (pageCount - size > scope.page && scope.page > size) {

                    start = scope.page - scope.adjacent;
                    finish = scope.page + scope.adjacent;

                    addFirst(scope, start);
                    addRange(start, finish, scope);
                    addLast(pageCount, scope, finish);

               } else {

                    start = pageCount - (1 + size + (scope.adjacent - 1));
                    finish = pageCount;

                    addFirst(scope, start);
                    addRange(start, finish, scope);

               }
          }
          addNext(scope, pageCount);

     }


     // The actual angular directive return
     return {
          restrict: 'EA',
          scope: {
               page: '=',
               pageSize: '=',
               total: '=',
               dots: '@',
               hideIfEmpty: '@',
               ulClass: '@',
               activeClass: '@',
               disabledClass: '@',
               adjacent: '@',
               scrollTop: '@',
               showPrevNext: '@',
               pagingAction: '&'
          },
          template:
                  '<ul ng-hide="Hide" ng-class="ulClass"> ' +
                  '<li ' +
                  'title="{{Item.title}}" ' +
                  'ng-class="Item.liClass" ' +
                  'ng-click="Item.action()" ' +
                  'ng-repeat="Item in List"> ' +
                  '<span ng-bind="Item.value"></span> ' +
                  '</ul>',
          link: function (scope, element, attrs) {

               // Hook in our watched items 
               scope.$watchCollection('[page,pageSize,total]', function () {
                    build(scope, attrs);
               });
          }
     };
}


/**
 * pageTitle - Directive for set Page title - mata title
 */
function pageTitle($rootScope, $timeout) {
     return {
          link: function (scope, element) {
               var listener = function (event, toState, toParams, fromState, fromParams) {
                    // Default title
                    var title = '6Connect | AngularJS Responsive WebApp';
                    // Create your own title pattern
                    if (toState.data && toState.data.pageTitle)
                         title = '6Connect | ' + toState.data.pageTitle;
                    $timeout(function () {
                         element.text(title);
                    });
               };
               $rootScope.$on('$stateChangeStart', listener);
          }
     }
}
;

/**
 * sideNavigation - Directive for run metsiMenu on sidebar navigation
 */
function sideNavigation($timeout) {
     return {
          restrict: 'A',
          link: function (scope, element) {
               // Call the metsiMenu plugin and plug it to sidebar navigation
               element.metisMenu();

               // Colapse menu in mobile mode after click on element
               var menuElement = $('#side-menu a:not([href$="\\#"])');
               menuElement.click(function () {

                    if ($(window).width() < 769) {
                         $("body").toggleClass("show-sidebar");
                    }
               });


          }
     };
}
;

/**
 * minimalizaSidebar - Directive for minimalize sidebar
 */
function minimalizaMenu($rootScope) {
     return {
          restrict: 'EA',
          template: '<div class="header-link hide-menu" ng-click="minimalize()"><i class="fa fa-bars"></i></div>',
          controller: function ($scope, $element) {

               $scope.minimalize = function () {
                    if ($(window).width() < 769) {
                         $("body").toggleClass("show-sidebar");
                    } else {
                         $("body").toggleClass("hide-sidebar");
                    }
               }
          }
     };
}
;


/**
 * sparkline - Directive for Sparkline chart
 */
function sparkline() {
     return {
          restrict: 'A',
          scope: {
               sparkData: '=',
               sparkOptions: '=',
          },
          link: function (scope, element, attrs) {
               scope.$watch(scope.sparkData, function () {
                    render();
               });
               scope.$watch(scope.sparkOptions, function () {
                    render();
               });
               var render = function () {
                    $(element).sparkline(scope.sparkData, scope.sparkOptions);
               };
          }
     }
}
;

/**
 * icheck - Directive for custom checkbox icheck
 */
function icheck($timeout) {
     return {
          restrict: 'A',
          require: 'ngModel',
          link: function ($scope, element, $attrs, ngModel) {
               return $timeout(function () {
                    var value;
                    value = $attrs['value'];

                    $scope.$watch($attrs['ngModel'], function (newValue) {
                         $(element).iCheck('update');
                    })

                    return $(element).iCheck({
                         checkboxClass: 'icheckbox_square-green',
                         radioClass: 'iradio_square-green'

                    }).on('ifChanged', function (event) {
                         if ($(element).attr('type') === 'checkbox' && $attrs['ngModel']) {
                              $scope.$apply(function () {
                                   return ngModel.$setViewValue(event.target.checked);
                              });
                         }
                         if ($(element).attr('type') === 'radio' && $attrs['ngModel']) {
                              return $scope.$apply(function () {
                                   return ngModel.$setViewValue(value);
                              });
                         }
                    });
               });
          }
     };
}


/**
 * panelTools - Directive for panel tools elements in right corner of panel
 */
function panelTools($timeout) {
     return {
          restrict: 'A',
          scope: true,
          templateUrl: BASE_URL + '/app/panel_tools',
          controller: function ($scope, $element) {
               // Function for collapse ibox
               $scope.showhide = function () {
                    var hpanel = $element.closest('div.hpanel');
                    var icon = $element.find('i:first');
                    var body = hpanel.find('div.panel-body');
                    var footer = hpanel.find('div.panel-footer');
                    body.slideToggle(300);
                    footer.slideToggle(200);
                    // Toggle icon from up to down
                    icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
                    hpanel.toggleClass('').toggleClass('panel-collapse');
                    $timeout(function () {
                         hpanel.resize();
                         hpanel.find('[id^=map-]').resize();
                    }, 50);
               },
                       // Function for close ibox
                       $scope.closebox = function () {
                            var hpanel = $element.closest('div.hpanel');
                            hpanel.remove();
                       }

          }
     };
}
;


/**
 * smallHeader - Directive for page title panel
 */
function smallHeader() {
     return {
          restrict: 'A',
          scope: true,
          controller: function ($scope, $element) {
               $scope.small = function () {
                    var icon = $element.find('i:first');
                    var breadcrumb = $element.find('#hbreadcrumb');
                    $element.toggleClass('small-header');
                    breadcrumb.toggleClass('m-t-lg');
                    icon.toggleClass('fa-arrow-up').toggleClass('fa-arrow-down');
               }
          }
     }
}

function animatePanel($timeout, $state) {
     return {
          restrict: 'A',
          link: function (scope, element, attrs) {

               //Set defaul values for start animation and delay
               var startAnimation = 0;
               var delay = 0.06;   // secunds
               var start = Math.abs(delay) + startAnimation;

               // Store current state where directive was start
               var currentState = $state.current.name;

               // Set default values for attrs
               if (!attrs.effect) {
                    attrs.effect = 'zoomIn'
               }
               ;
               if (attrs.delay) {
                    delay = attrs.delay / 10
               } else {
                    delay = 0.06
               }
               ;
               if (!attrs.child) {
                    attrs.child = '.row > div'
               } else {
                    attrs.child = "." + attrs.child
               }
               ;

               // Get all visible element and set opactiy to 0
               var panel = element.find(attrs.child);
               panel.addClass('opacity-0');

               // Count render time
               var renderTime = panel.length * delay * 1000 + 700;

               // Wrap to $timeout to execute after ng-repeat
               $timeout(function () {

                    // Get all elements and add effect class
                    panel = element.find(attrs.child);
                    panel.addClass('animated-panel').addClass(attrs.effect);

                    // Add delay for each child elements
                    panel.each(function (i, elm) {
                         start += delay;
                         var rounded = Math.round(start * 10) / 10;
                         $(elm).css('animation-delay', rounded + 's')
                         // Remove opacity 0 after finish
                         $(elm).removeClass('opacity-0');
                    });

                    // Clear animate class after finish render
                    $timeout(function () {

                         // Check if user change state and only run renderTime on current state
                         if (currentState == $state.current.name) {
                              // Remove effect class - fix for any backdrop plgins (e.g. Tour)
                              $('.animated-panel:not([ng-repeat]').removeClass(attrs.effect);
                         }
                    }, renderTime)

               });

          }
     }
}

function landingScrollspy() {
     return {
          restrict: 'A',
          link: function (scope, element, attrs) {
               element.scrollspy({
                    target: '.navbar-fixed-top',
                    offset: 80
               });
          }
     }
}


