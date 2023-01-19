var ses = localStorage.getItem("token");
let em = localStorage.getItem("mail")
let utype = localStorage.getItem("utype");
let st = window.location.pathname;

let details = {
	pox:"890 K'la (Ug)",
	loc: "5 Str, Kampala Rd",
	mail: "cmmf@cmmf.fueless.co.ug",
	phone1:"+256788227844",
	phone2:"+256788227844",
	cmmf:"CMMF Sacco"
}
if(ses=='' || ses==null){
	$('#nav').css({display:"none"});
	$("#p_title").css({display:"none"});
}
if(em=='' || em==null && st != '/login'){
	  window.location = "/login";
}
$("#log_mail").text(localStorage.getItem('mail'))
$("#log_name").text(localStorage.getItem('full_name'))
$("#loged_name").text(localStorage.getItem('long_name'))
let token = localStorage.getItem("token");
let user_mail = localStorage.getItem("mail");
let xtime = 5000;
let headers = {
	"content-type":"application/json",
	"auth":token,
	"accept":"*/*",
}
// console.log(user_mail)
const toast = (x, c = 'success') =>{
	// setTimeout(() => {
		$("#alerting").html(`<div class="alert alert-${c} alert-dismissible fade show text-center" role="alert">
			<i class="bi bi-info-circle me-1"></i>   ${x} 
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>`)
	// }, 6000);
	setTimeout(() => {
		$("#alerting").hide();
	}, xtime);
	// toast('','');
	return;

	//Materialize.toast(`<p style="white-space:pre-wrap; word-break:break-word; text-align:center;">${x}</p>`, xtime);
}
const page_title = (title) =>{
	document.title = `${title} > CMMF`;
	$("#h10").text(title);
	$("#h12").text(title);
}

const go_to_page = (x=[]) =>{
// alert(x)
	let j = '';
	for(let m of x){
		j = `${j}/${m}`
	}	
	window.location = j;	
}
const print_now = (prt='') =>{
	$("#print_title").text(prt)
$('#btn-group').css({display:'none'})
	$('.pagetitle').css({display:'none'});
	$("#print-title").css({display:"inline-block"});
	$('.btn').css({display:'none'})
	$('#nav').css({display:'none'});
	$("#ghead").append(`<p style="text-align:center; ">${prt}</p>`)
	$("#ghead").show();
	$(".dont-print").hide();

	$("#pox").text(details.pox)
	$("#loc").text(details.loc)
	$("#mail").text(details.mail)
	$("#phone1").text(details.phone1)
	$("#phone2").text(details.phone2)
	$("#cmmf").text(details.cmmf)
	window.print();
	$("#ghead").hide();
	$("#print-title").css({display:"none"});
	$('.pagetitle').css({display:'inline-block'});
	$('.btn').css({display:'inline-block'})
	$('#nav').css({display:'inline-block'})
$('#btn-group').css({display:'inline-block'})
$(".dont-print").show();
}

let nm = new Intl.NumberFormat("en-US")



const number_with_zeros = (i, x) =>{
	var p = 10**x - i
	var ol = p.toString().length - i.toString().length
	if(ol>0){
		
		var r = ""
		for(var u=0; u<ol; u++){
			r += "0"
		}
		r += i
		return r;
	}else{
		return i;
	}

}

const Input = (obj) =>{
    // console.log(obj)
    $(`#${obj.div}`).html(`<div class="row mb-3">
    <label for="${obj.id}" class="col-sm-2 col-form-label">${obj.label}</label>
    <div class="col-sm-10">
      <input 
        type="${obj.type}" 
        value="${obj.value}" 
        id="${obj.id}" 
		oninput="()=>${obj.oninput}"
        class="form-control rounded-pill" 
        data-length="${obj.dlength}"
        ${obj.dis} ${obj.required}
        >
    </div>
  </div>`)
}

const Button = (obj) =>{
  // console.log(obj)
  $(`#${obj.div}`).html(`<button type="${obj.type}"  class="btn btn-outline-${obj.btn} rounded-pill btn-${obj.size}" ${obj.dis}>${obj.label} <i class="bi bi-${obj.icon}"></i></button>`);
}
const Select = (obj) =>{
	// let sel = obj.value==this.value?'selected':'';
    let jo = `    
    <div class="row mb-3">
    <label class="col-sm-2 col-form-label">${obj.label}</label>
    <div class="col-sm-10">
      <select id="${obj.id}"  value="${obj.value}" class="form-select rounded-pill" aria-label="Default select example">
        <option>Open this select menu</option>`
    for(let m of obj.options){
		let sel = obj.value==m.value?'selected':'';
        jo += `<option value="${m.value}" ${sel}>${m.title}</option>`
    }
    jo +=  `</select>
    </div>
    </div>
    `
    // console.log(jo)
    $(`#${obj.div}`).html(jo);
}

const Anchor = (obj) =>{
  $(`#${obj.div}`).html(`<a href="${obj.href}"  class="btn btn-outline-${obj.btn} rounded-pill btn-sm">${obj.text}</a>`);
}

const TextArea  = (obj) =>{
  $(`#${obj.div}`).html(`<div class="form-floating mb-3">
  <textarea class="form-control" placeholder="${obj.placeholder}" id="${obj.id}" style="height: 100px;"></textarea>
  <label for="${obj.id}">${obj.label}</label>
</div>`);
}

const Table = (obj) =>{
	let head = "<table class='table table-striped'><tr>";
	for(let t of obj.head){
		head += `<th>${t}</th>`
	}
	head += `</tr><tbody>`
	for(let m of obj.body){
		head += `<tr>`
		for(let c of m){
			head += `<td>${c}</td>`
		}
		head += `</tr>`
		
	}
	head += `</tbody>`

	$(`#${obj.div}`).html(`${head}`);

}


const loan_status = (x) =>{
	let m  = ''
	if(x == 1){
		m = "Pending"
	}else if(x==2){
		m  = "Approved"
	}else if(x==3){
		m  = "Renewed"
	}else{
		m  = "Settled"
	}
	return m;
}

const logout =()=>{
	let p = confirm("Your session is expired, start new one!...")
	if(p){
		$.ajax({
			type: "post",
			url: `${base_url}/api/user_login.php`,
			data: JSON.stringify({uname:localStorage.getItem("mail")}),
			dataType: "json",
			success: function (response) {
				// alert(response.otp)
				new_f();
			}
		});
		
		
	}else{
		window.location = "/logout"
	}
}

const new_f = () =>{
	let x = prompt(`Enter OTP sent to ${user_mail} to continue!`)

	$.ajax({
		type: "post",
		url: `${base_url}/api/otp.php`,
		data: JSON.stringify({mail:user_mail, otp:x}),
		dataType: "json",
		success: function (response) {
			// console.log(response)
			if(response.status==1){
				localStorage.setItem("token", response.token)
				location.reload();
			}else{
				alert("Wrong OTP...");
				location.reload();
			}
		}
	});
}

const user_types = (i) =>{
	let m = ""
	$.ajax({
		type: "get",
		url: `${base_url}/api/userAPI.php?type_name=${i}`,
		headers,
		dataType: "json",
		success: function (response) {			
			return response.name
		}
	});
	
}

$("button:submit").on("click", ()=>{
	setTimeout(
		$("button:submit").attr("disabled", true), xtime
	)
})

$(document).ready(()=>{
	if(utype!=5){
		$(".loan-officer").hide();
		$(".press-loan-officer").attr("disabled", true)
		$("a.press-loan-officer").attr("href","#")
		$("a.press-loan-officer").click((e)=>{
			alert("You have no privilege to perform this operation...")
		})
	}
	if(utype!=2){
		$(".secretary").hide();
		$(".press-secretary").attr("disabled", true)
		$("button.secretary").hide()
	}

	if(utype!=3){
		$(".chairman").hide();
	}else{
		$(".chairman").show();
	}
	if(utype!=1){
		$(".super").hide();
	}else{
		$(".super").show();
	}
	if(utype==3 || utype==1){
		$(".chairman").show();
		$(".super").show();
	}
	
})

const allow_url = (arr) =>{
	if(arr.indexOf(utype)!=-1){
		let t = confirm("You are not allow to access this resource")
		if(t){
			window.history.go(-1)
		}else{
			window.history.go(-1)
		}
	}
}

const make_sum = (m) =>{
	let j = 0
	try{
		j += m
	}catch(TypeError){
		j = "T.B.D"
	}
	return j=="T.B.D"?"T.B.D":Number(j)
}





