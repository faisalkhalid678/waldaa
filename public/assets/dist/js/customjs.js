$(document).on('click', '.mark_new', function () {
    var base_url = $('.base_url').val();
    var song_id = $(this).attr('song_id');
    var current_value = $(this).text();

    if (current_value === 'Yes') {
        $(this).addClass('badge-danger');
        $(this).removeClass('badge-info');
        $(this).text('No');
        $(this).attr('title', 'Add To New Songs List.');
        var song_val = '0';
        var message = 'Song Removed from the New List Successfully';

    } else {
        $(this).removeClass('badge-danger');
        $(this).addClass('badge-info');
        $(this).text('Yes');
        $(this).attr('title', 'Remove from New Songs List.');
        var song_val = '1';
        var message = 'Song Added to the New List Successfully';
    }

    $.ajax({
        url: base_url + 'admin/songs/mark-new',
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {song_id: song_id, is_new: song_val},
        dataType: "json",
        cache: false,
        success: function (data) {
            if (data.status == 'true') {
                notificationMessage('Success', message, 'success');
            } else {
                notificationMessage('Failed', 'Song Not Successfully added to New Songs List', 'error');
            }
        }
    });
});


$(document).on('click', '.mark_featured', function () {
    var base_url = $('.base_url').val();
    var song_id = $(this).attr('song_id');
    var current_value = $(this).text();

    if (current_value === 'Yes') {
        $(this).addClass('badge-danger');
        $(this).removeClass('badge-info');
        $(this).text('No');
        $(this).attr('title', 'Add To New Songs List.');
        var song_val = '0';
        var message = 'Song Removed from the Featured Songs List Successfully';
    } else {
        $(this).removeClass('badge-danger');
        $(this).addClass('badge-info');
        $(this).text('Yes');
        $(this).attr('title', 'Remove from New Songs List.');
        var song_val = '1';
        var message = 'Song Added to the Featured Songs List Successfully';

    }

    $.ajax({
        url: base_url + 'admin/songs/mark-featured',
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {song_id: song_id, is_featured: song_val},
        dataType: "json",
        cache: false,
        success: function (data) {
            if (data.status == 'true') {
                notificationMessage('Success', message, 'success');
            } else {
                notificationMessage('Failed', 'Song Not Successfully added to Featured Songs List', 'error');
            }
        }
    });
});

function notificationMessage(Heading, text, icon) {
    $.toast({
        heading: Heading,
        text: text,
        position: 'top-right',
        loaderBg: '#ff6849',
        icon: icon,
        hideAfter: 3500,
        stack: 6
    });
}

$(document).on('change', '.album_artist', function () {
    var base_url = $('.base_url').val();
    var artistName = $('.album_artist :selected').text();
    var artist_id = $(this).val();
    $.ajax({
        url: base_url + "admin/albums/get-songs-by-artist",
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {artistId: artist_id},
        dataType: "json",
        success: function (data) {
            if (data.status === '200') {
                if ($('.artist' + artist_id).length === 0) {
                    $('.songsdiv').append('<div class="row artist_songs artist' + artist_id + '" style="margin-bottom: 40px;"><div class="col"><div class="row"><div class="col-md-6"><h3 class="artist_name' + artist_id + '"></h3></div><div class="col-md-6"><h4 class="text-danger remove_artist" style="cursor: pointer;">Remove From Album</h4></div></div><select class="songs_dropdown' + artist_id + '" multiple="multiple" size="10" name="songs[' + artist_id + '][]" title="songs[]"></select></div></div>');
                    $('.artist_songs').find('.artist_name' + artist_id).text(artistName);
                   
                    
                    var songsArr = data.data;
                    var i;
                    for (i = 0; i < songsArr.length; i++) {
                        $('.songs_dropdown' + artist_id).append('<option value="' + songsArr[i].songId + '">' + songsArr[i].songName + '</option>');
                    }
                }



                var demo1 = $('select[title="songs[]"]').bootstrapDualListbox({
                    nonSelectedListLabel: 'Available Songs',
                    selectedListLabel: 'Selected Songs',
                    preserveSelectionOnMove: 'moved',
                    moveAllLabel: 'Move all',
                    removeAllLabel: 'Remove all'
                });
            }
        }});
});


$(document).on('click', '.remove_artist', function () {
    $(this).closest('.artist_songs').remove();
});

$('.album_form').submit(function () {
    if ($('.artist_songs').length === 0) {
        alert('No Artist and Songs Selected for this album.');
        return false;
    }
});