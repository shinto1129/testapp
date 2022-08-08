$(function(){
    let dataBox;
    let userId;
    $("#btn1").click(function () {
        userId = $(this).data('user-id');
        $(this).toggleClass('btn2');
        $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
      });
        $.ajax({
          //POST通信
          type: "post",
          //ここでデータの送信先URLを指定します。
          url: "/chenge",
          dataType: "json",
          data: {
            'user_id': userId,
          },

        })
          //通信が成功したとき
          .then((res) => {
            console.log(res);
          })
          //通信が失敗したとき
          .fail((error) => {
            console.log(error.statusText);
          });
      });
      $(".delete").click(function(){
        if(!confirm("取り消しますか?")){
            return false;
        }else{
            return true;
        }
      })
      $(".edit").click(function(){
        if(!confirm("内容は間違いありませんか?")){
            return false;
        }else{
            return true;
        }
      })



      $('#testModal').on('show.bs.modal', function (event) {
        //モーダルを開いたボタンを取得
        var button = $(event.relatedTarget);
        //data-periodの値取得
        var periodVal = button.data('period');
        var weekVal = button.data('week');
        //モーダルを取得
        var modal = $(this);

        var select1 = document.getElementById("period-select");
        var select2 = document.getElementById("week-select");
        select1.options[periodVal-1].selected = true
        select2.options[weekVal-1].selected = true
        //受け取った値をspanタグのとこに表示
        modal.find('.modal-header span#morau').text(periodVal+'の');
    });
});
