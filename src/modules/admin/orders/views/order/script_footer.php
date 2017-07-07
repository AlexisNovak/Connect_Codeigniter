<script>
     $(function () {
          var sampleTags = [];
          $('#orderrefs').tagit({
               placeholderText: '<?= lang('order_ref_ph') ?>'
          });
          $('#ordertags').tagit({
               placeholderText: '<?= lang('tags_ph') ?>',
               tagSource: function (search, showChoices) {
                    var filter = search.term.toLowerCase();
                    $.ajax({url: BASE_URL + 'orders/tags/taglist',
                         type: 'post',
                         data: {filter: filter},
                         success: function (data) {
                              console.log(data);
                              var choices = $.grep(data, function (element) {
// Only match autocomplete options that begin with the search term.
// (Case insensitive.)
                                   return (element.toLowerCase().indexOf(filter) === 0);
                              });
                              showChoices(choices);
                         },
                         dataType: 'json'
                    });
               }
               // allowSpaces: true
          });
          //-------------------------------
          // Tag events
          //-------------------------------
          var eventTags = $('#eventTags');
          var addEvent = function (text) {
               $('#events_container').append(text + '<br>');
          };
          eventTags.tagit({
               availableTags: sampleTags,
               beforeTagAdded: function (evt, ui) {
                    if (!ui.duringInitialization) {
                         addEvent('beforeTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
               },
               afterTagAdded: function (evt, ui) {
                    if (!ui.duringInitialization) {
                         addEvent('afterTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
               },
               beforeTagRemoved: function (evt, ui) {
                    addEvent('beforeTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
               },
               afterTagRemoved: function (evt, ui) {
                    addEvent('afterTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
               },
               onTagClicked: function (evt, ui) {
                    addEvent('onTagClicked: ' + eventTags.tagit('tagLabel', ui.tag));
               },
               onTagExists: function (evt, ui) {
                    addEvent('onTagExists: ' + eventTags.tagit('tagLabel', ui.existingTag));
               }
          });
     });
</script>