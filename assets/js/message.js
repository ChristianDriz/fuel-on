        //for last seen update
        let lastSeenUpdate = function(){
            $.get("assets/ajax/active_status.php");
      }
      lastSeenUpdate();
      setInterval(lastSeenUpdate, 1000);

      //for message notif
      let fetchMessageNotif = function(){
        $.get("assets/ajax/unread_message_count.php", 
          {
          userID: <?php echo $userID ?>
          },
          function(data){
              if (data != 0){
                  $(".message-counter").html(data);
              }
          });
      }

      fetchMessageNotif();
      //auto update every .5 sec
      setInterval(fetchMessageNotif, 500);