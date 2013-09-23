$(document).ready(function() {

  /**
   * Progressbar funtionunality
   */
  var progressbar = $("#progressbar"),
  link,
  comment,
          progressLabel = $(".progress-label");

      progressbar.progressbar({
        value: false,
        change: function() {
          progressLabel.text(progressbar.progressbar("value") + "%");
        },
        complete: function() {
          progressLabel.text("Sidan kommer att laddas om nu!");
        }
      });

      function progress() {
        var val = progressbar.progressbar("value") || 0;

        progressbar.progressbar("value", val + 1);

        if (val < 99) {
          setTimeout(progress, 280);
        }
      }
      setTimeout(progress, 100);

/**
 * jQuey AJAX function to get the Images links as JSON object from the PHP proxy
 * after sending the URL as a $_POST
 *
 */
  $.ajax({
    url: '../bouvet/api/imageProccessor.php',
    type: 'POST',
    data: {
      url: 'http%3A%2F%2Fbouvet.lobs.se%2Frekrytering_flera.php'
    },
    dataType: 'json',
    beforeSend: function() {
      $('.loading').show();
    },
    success: function(data) {
      $.each(data, function(DataKey, DataValue) {
        if (DataValue && !$.isEmptyObject(DataValue)) {
          $.each(DataValue, function(DataValueKey, DataValueReccord) {
            $.each(DataValueReccord, function() {
              link = DataValueReccord[0],
              comment = DataValueReccord[1];
            });

            // There is an image or more
            if(link && link !== '') {
              //If the images has comments show them too
              if(comment && comment !== 'null') {
                $('.link-list').append(
                  '<li class="link-list-item">' +
                    '<a class="image-link" href="' + link + '" target="_blank">' +
                      '<img class="link-image" style="width: 30%; height:20%;" src="' + link + '" />' +
                    '</a>' +
                    '<h2 class="image-comment">Kommentar: ' + comment + '</h2>' +
                  '</li>'
                );
              } else { //else show only images
                $('.link-list').append(
                  '<li class="link-list-item">' +
                    '<a class="image-link" href="' + link + '" target="_blank">' +
                      '<img class="link-image" style="width: 30%; height:20%;" src="' + link + '" />' +
                    '</a>' +
                  '</li>'
                );
              }
            }
          });
        }
      });

      //Show the "No image" image file if the object has no images to show
      if($.isEmptyObject(data)) {
        $('.link-list').append(
          '<li class="link-list-item"><strong>Ingen bild</strong>' +
            '<img class="link-image" src="../bouvet/images/no_image.png" />' +
          '</li>'
        );
      }
      $('.loading').hide();
    },
    
    //Auto reload the document/browser after 30 seconds
    complete: setTimeout(function() {
      window.location.reload();
    }, 30000)
  });

 // Reload the document/browser when click on the reload button
  $('.reload-button').on('click', function() {
    window.location.reload();
  });

 //Slide toogle ABOUT ME information when click on "ABOUT" text
  $('.toggle-me').on('click', function(){
    $('.about').slideToggle('300');
  });
});