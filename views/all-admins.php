<h4 class="center-align">CMMF Admin</h4>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>           
        </tr>
    </thead>
    <tbody id="adminList">

    </tbody>
</table>

<div class="fixed-action-btn" id="admin">
<a class="btn-floating btn-large green" href="/add-admin">
    <i class="large material-icons">add</i>
</a>
</div>
<script>
    page_title('Admin');
    let bn = user_mail!="kibirigetwaha123@gmail.com"
    
    const get_admins =() =>{
        $.ajax({
            type: "GET",
            url: `${base_url}/api/userAPI.php`,
            headers:headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                // alert(user_mail)
                let x  = ''
                for(let m of response.admin){
                    x +=`<tr class='${m.status==1?"":"red"}' ><td>${m.lname} ${m.fname}</td><td>${m.mail}</td><td ${bn?this.visibility='visible':this.visibility='hidden'}><a href="/admin/${m.user_id}/edit"><i class="small material-icons green-text">edit</i></a></td><td ${bn?this.visibility='visible':this.visibility='hidden'}><i class="small material-icons blue-text" onclick='change_status("${m.user_id}", "${m.mail}", "${m.status}")'>manage_accounts</i></td></tr>`;
                }
                $("#adminList").html(x)
            }
        });
    }
    // const do_nothing = () =>{

    // }
    // const loo = (a, b, c) =>{
    //     if(user_mail=="tkibirige@cmmf.com"){
    //         cpp(a, b, c)
    //     }
    // }
    get_admins();
    const change_status = (id, ma, s) =>{
        
        let  msg = `Activate ${ma}'s account`
        if(id!=1){
            if(s==1){
                msg = `Deactivate ${ma}'s account`
            }
        
        let x = confirm(msg);
        if(x){
            $.ajax({
            type: "get",
            url: `${base_url}/api/userAPI.php?status=${id}`,
            headers,
            dataType: "json",
            success: function (response) {
                if(response.status){
                    toast(response.message, xtime)
                    setTimeout(() => {
                        get_admins();
                    }, xtime);
                }else{
                    toast(response.error, xtime) 
                }
            }
    });
        }
        }
    
    }

    // const cpp =(a, b, c)=> {
    //     if(a==1){
    //         do_nothing()
    //     }else{
    //         change_status(a, b, c)
    //     }
    // }

    if(bn){
        $("#admin").show()
    }
    else{
        $("#admin").hide();
    }
</script>