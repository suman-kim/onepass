


$(function () {
    $("#checkAll").change(function () {
       
        $("input[name='choice[]']").prop("checked", $(this).prop("checked"));
    });
    $("input[name='choice[]']").click(function () {
        if ($("#checkAll").is(":checked") == true) {
            $("#checkAll").prop("checked", false);
        }
    });
});

$(function () {
   
    $("#checkAll2").change(function () {
       
        $("input[name='device_ids']").prop("checked", $(this).prop("checked"));
    });
    $("input[name='device_ids']").click(function () {
        if ($("#checkAll2").is(":checked") == true) {
            $("#checkAll2").prop("checked", false);
        }
    });
});
//var ip = "hB2/YLi4uLjZp5sQkDUHmlbgXDTgnoZ9BY8MM5uRCQso";
const ip = "http://192.168.2.187:9980";

var Aes = {}; 
Aes.cipher = function(input, w) {
    var Nb = 4;
    var Nr = w.length/Nb - 1; 
    var state = [[],[],[],[]];
    for (var i=0; i<4*Nb; i++) {
        state[i%4][Math.floor(i/4)] = input[i];
    }
    state = Aes.addRoundKey(state, w, 0, Nb);
    for (var round=1; round<Nr; round++) {
        state = Aes.subBytes(state, Nb);
        state = Aes.shiftRows(state, Nb);
        state = Aes.mixColumns(state, Nb);
        state = Aes.addRoundKey(state, w, round, Nb);
    }
    state = Aes.subBytes(state, Nb);
    state = Aes.shiftRows(state, Nb);
    state = Aes.addRoundKey(state, w, Nr, Nb);
    var output = new Array(4*Nb);
    for (var i=0; i<4*Nb; i++) {
        output[i] = state[i%4][Math.floor(i/4)];
    }
    return output;
}
Aes.keyExpansion = function(key) {
    var Nb = 4;
    var Nk = key.length/4;
    var Nr = Nk + 6;
    var w = new Array(Nb*(Nr+1));
    var temp = new Array(4);
    for (var i=0; i<Nk; i++) {
        var r = [key[4*i], key[4*i+1], key[4*i+2], key[4*i+3]];
        w[i] = r;
    }
    for (var i=Nk; i<(Nb*(Nr+1)); i++) {
        w[i] = new Array(4);
        for (var t=0; t<4; t++) {
            temp[t] = w[i-1][t];
        }
        if (i % Nk == 0) {
        temp = Aes.subWord(Aes.rotWord(temp));
        for (var t=0; t<4; t++) {
            temp[t] ^= Aes.rCon[i/Nk][t];
        }
        } else if (Nk > 6 && i%Nk == 4) {
        temp = Aes.subWord(temp);
        }
        for (var t=0; t<4; t++) {
            w[i][t] = w[i-Nk][t] ^ temp[t];
        }
    }
    return w;
}
Aes.subBytes = function(s, Nb) {
    for (var r=0; r<4; r++) {
        for (var c=0; c<Nb; c++) {
            s[r][c] = Aes.sBox[s[r][c]];
        }
    }
    return s;
}
Aes.shiftRows = function(s, Nb) {
    var t = new Array(4);
    for (var r=1; r<4; r++) {
        for (var c=0; c<4; c++) {
            t[c] = s[r][(c+r)%Nb];
        }
        for (var c=0; c<4; c++) {
            s[r][c] = t[c];
        }
    }          
    return s;  
}
Aes.mixColumns = function(s, Nb) {  
    for (var c=0; c<4; c++) {
        var a = new Array(4);  
        var b = new Array(4); 
        for (var i=0; i<4; i++) {
        a[i] = s[i][c];
        b[i] = s[i][c]&0x80 ? s[i][c]<<1 ^ 0x011b : s[i][c]<<1;
        }
        s[0][c] = b[0] ^ a[1] ^ b[1] ^ a[2] ^ a[3];
        s[1][c] = a[0] ^ b[1] ^ a[2] ^ b[2] ^ a[3];
        s[2][c] = a[0] ^ a[1] ^ b[2] ^ a[3] ^ b[3];
        s[3][c] = a[0] ^ b[0] ^ a[1] ^ a[2] ^ b[3];
    }
    return s;
}
Aes.addRoundKey = function(state, w, rnd, Nb) {
    for (var r=0; r<4; r++) {
        for (var c=0; c<Nb; c++) {
            state[r][c] ^= w[rnd*4+c][r];
        }
    }
    return state;
}
Aes.subWord = function(w) {   
    for (var i=0; i<4; i++) {
        w[i] = Aes.sBox[w[i]];
    }
    return w;
}
Aes.rotWord = function(w) {   
    var tmp = w[0];
    for (var i=0; i<3; i++) {
        w[i] = w[i+1];
    }
    w[3] = tmp;
    return w;
}
Aes.sBox =  [0x63,0x7c,0x77,0x7b,0xf2,0x6b,0x6f,0xc5,0x30,0x01,0x67,0x2b,0xfe,0xd7,0xab,0x76,
            0xca,0x82,0xc9,0x7d,0xfa,0x59,0x47,0xf0,0xad,0xd4,0xa2,0xaf,0x9c,0xa4,0x72,0xc0,
            0xb7,0xfd,0x93,0x26,0x36,0x3f,0xf7,0xcc,0x34,0xa5,0xe5,0xf1,0x71,0xd8,0x31,0x15,
            0x04,0xc7,0x23,0xc3,0x18,0x96,0x05,0x9a,0x07,0x12,0x80,0xe2,0xeb,0x27,0xb2,0x75,
            0x09,0x83,0x2c,0x1a,0x1b,0x6e,0x5a,0xa0,0x52,0x3b,0xd6,0xb3,0x29,0xe3,0x2f,0x84,
            0x53,0xd1,0x00,0xed,0x20,0xfc,0xb1,0x5b,0x6a,0xcb,0xbe,0x39,0x4a,0x4c,0x58,0xcf,
            0xd0,0xef,0xaa,0xfb,0x43,0x4d,0x33,0x85,0x45,0xf9,0x02,0x7f,0x50,0x3c,0x9f,0xa8,
            0x51,0xa3,0x40,0x8f,0x92,0x9d,0x38,0xf5,0xbc,0xb6,0xda,0x21,0x10,0xff,0xf3,0xd2,
            0xcd,0x0c,0x13,0xec,0x5f,0x97,0x44,0x17,0xc4,0xa7,0x7e,0x3d,0x64,0x5d,0x19,0x73,
            0x60,0x81,0x4f,0xdc,0x22,0x2a,0x90,0x88,0x46,0xee,0xb8,0x14,0xde,0x5e,0x0b,0xdb,
            0xe0,0x32,0x3a,0x0a,0x49,0x06,0x24,0x5c,0xc2,0xd3,0xac,0x62,0x91,0x95,0xe4,0x79,
            0xe7,0xc8,0x37,0x6d,0x8d,0xd5,0x4e,0xa9,0x6c,0x56,0xf4,0xea,0x65,0x7a,0xae,0x08,
            0xba,0x78,0x25,0x2e,0x1c,0xa6,0xb4,0xc6,0xe8,0xdd,0x74,0x1f,0x4b,0xbd,0x8b,0x8a,
            0x70,0x3e,0xb5,0x66,0x48,0x03,0xf6,0x0e,0x61,0x35,0x57,0xb9,0x86,0xc1,0x1d,0x9e,
            0xe1,0xf8,0x98,0x11,0x69,0xd9,0x8e,0x94,0x9b,0x1e,0x87,0xe9,0xce,0x55,0x28,0xdf,
            0x8c,0xa1,0x89,0x0d,0xbf,0xe6,0x42,0x68,0x41,0x99,0x2d,0x0f,0xb0,0x54,0xbb,0x16];
Aes.rCon = [[0x00, 0x00, 0x00, 0x00],
            [0x01, 0x00, 0x00, 0x00],
            [0x02, 0x00, 0x00, 0x00],
            [0x04, 0x00, 0x00, 0x00],
            [0x08, 0x00, 0x00, 0x00],
            [0x10, 0x00, 0x00, 0x00],
            [0x20, 0x00, 0x00, 0x00],
            [0x40, 0x00, 0x00, 0x00],
            [0x80, 0x00, 0x00, 0x00],
            [0x1b, 0x00, 0x00, 0x00],
            [0x36, 0x00, 0x00, 0x00]]; 
Aes.Ctr = {};
Aes.Ctr.encrypt = function(plaintext, password, nBits) {
    var blockSize = 16;  
    if (!(nBits==128 || nBits==192 || nBits==256)) {
        return ''; 
    }
    plaintext = Utf8.encode(plaintext);
    password = Utf8.encode(password);
    var nBytes = nBits/8; 
    var pwBytes = new Array(nBytes);
    for (var i=0; i<nBytes; i++) {
        pwBytes[i] = isNaN(password.charCodeAt(i)) ? 0 : password.charCodeAt(i);
    }
    var key = Aes.cipher(pwBytes, Aes.keyExpansion(pwBytes));
    key = key.concat(key.slice(0, nBytes-16));
    var counterBlock = new Array(blockSize);
    var nonce = (new Date()).getTime(); 
    var nonceSec = Math.floor(nonce/1000);
    var nonceMs = nonce%1000;
    for (var i=0; i<4; i++) {
        counterBlock[i] = (nonceSec >>> i*8) & 0xff;
    }
    for (var i=0; i<4; i++) {
        counterBlock[i+4] = nonceMs & 0xff; 
    }
    var ctrTxt = '';
    for (var i=0; i<8; i++) {
        ctrTxt += String.fromCharCode(counterBlock[i]);
    }
    var keySchedule = Aes.keyExpansion(key);
    
    var blockCount = Math.ceil(plaintext.length/blockSize);
    var ciphertxt = new Array(blockCount);
    
    for (var b=0; b<blockCount; b++) {
        for (var c=0; c<4; c++) {
            counterBlock[15-c] = (b >>> c*8) & 0xff;
        }
        for (var c=0; c<4; c++) {
            counterBlock[15-c-4] = (b/0x100000000 >>> c*8);
        }
        var cipherCntr = Aes.cipher(counterBlock, keySchedule); 
        
        var blockLength = b<blockCount-1 ? blockSize : (plaintext.length-1)%blockSize+1;
        var cipherChar = new Array(blockLength);
        
        for (var i=0; i<blockLength; i++) { 
        cipherChar[i] = cipherCntr[i] ^ plaintext.charCodeAt(b*blockSize+i);
        cipherChar[i] = String.fromCharCode(cipherChar[i]);
        }
        ciphertxt[b] = cipherChar.join(''); 
    }
    var ciphertext = ctrTxt + ciphertxt.join('');
    ciphertext = Base64.encode(ciphertext);
    
    return ciphertext;
}

Aes.Ctr.decrypt = function(ciphertext, password, nBits) {
    var blockSize = 16;
    if (!(nBits==128 || nBits==192 || nBits==256)) {
        return ''; 
    }
    ciphertext = Base64.decode(ciphertext);
    password = Utf8.encode(password);
    var nBytes = nBits/8; 
    var pwBytes = new Array(nBytes);
    for (var i=0; i<nBytes; i++) {
        pwBytes[i] = isNaN(password.charCodeAt(i)) ? 0 : password.charCodeAt(i);
    }
    var key = Aes.cipher(pwBytes, Aes.keyExpansion(pwBytes));
    key = key.concat(key.slice(0, nBytes-16)); 
    var counterBlock = new Array(8);
    ctrTxt = ciphertext.slice(0, 8);
    for (var i=0; i<8; i++) {
        counterBlock[i] = ctrTxt.charCodeAt(i);
    }
    var keySchedule = Aes.keyExpansion(key);
    var nBlocks = Math.ceil((ciphertext.length-8) / blockSize);
    var ct = new Array(nBlocks);
    for (var b=0; b<nBlocks; b++) {
        ct[b] = ciphertext.slice(8+b*blockSize, 8+b*blockSize+blockSize);
    }
    ciphertext = ct; 
    var plaintxt = new Array(ciphertext.length);
    for (var b=0; b<nBlocks; b++) {
        for (var c=0; c<4; c++) {
            counterBlock[15-c] = ((b) >>> c*8) & 0xff;
        }
        for (var c=0; c<4; c++) {
            counterBlock[15-c-4] = (((b+1)/0x100000000-1) >>> c*8) & 0xff;
        }
        var cipherCntr = Aes.cipher(counterBlock, keySchedule); 
        var plaintxtByte = new Array(ciphertext[b].length);
        for (var i=0; i<ciphertext[b].length; i++) {
        plaintxtByte[i] = cipherCntr[i] ^ ciphertext[b].charCodeAt(i);
        plaintxtByte[i] = String.fromCharCode(plaintxtByte[i]);
        }
        plaintxt[b] = plaintxtByte.join('');
    }
    var plaintext = plaintxt.join('');
    plaintext = Utf8.decode(plaintext);
    return plaintext;
}
var Base64 = {}; 
Base64.code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
Base64.encode = function(str, utf8encode) { 
    utf8encode =  (typeof utf8encode == 'undefined') ? false : utf8encode;
    var o1, o2, o3, bits, h1, h2, h3, h4, e=[], pad = '', c, plain, coded;
    var b64 = Base64.code;
    plain = utf8encode ? str.encodeUTF8() : str;
    c = plain.length % 3; 
    if (c > 0) { 
        while (c++ < 3) { 
            pad += '='; 
            plain += '\0'; 
        } 
    }
    for (c=0; c<plain.length; c+=3) { 
        o1 = plain.charCodeAt(c);
        o2 = plain.charCodeAt(c+1);
        o3 = plain.charCodeAt(c+2);
        
        bits = o1<<16 | o2<<8 | o3;
        
        h1 = bits>>18 & 0x3f;
        h2 = bits>>12 & 0x3f;
        h3 = bits>>6 & 0x3f;
        h4 = bits & 0x3f;
        e[c/3] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    }
    coded = e.join(''); 
    coded = coded.slice(0, coded.length-pad.length) + pad;
    return coded;
}
Base64.decode = function(str, utf8decode) {
    utf8decode =  (typeof utf8decode == 'undefined') ? false : utf8decode;
    var o1, o2, o3, h1, h2, h3, h4, bits, d=[], plain, coded;
    var b64 = Base64.code;
    coded = utf8decode ? str.decodeUTF8() : str;
    for (var c=0; c<coded.length; c+=4) { 
        h1 = b64.indexOf(coded.charAt(c));
        h2 = b64.indexOf(coded.charAt(c+1));
        h3 = b64.indexOf(coded.charAt(c+2));
        h4 = b64.indexOf(coded.charAt(c+3));
        bits = h1<<18 | h2<<12 | h3<<6 | h4;
        o1 = bits>>>16 & 0xff;
        o2 = bits>>>8 & 0xff;
        o3 = bits & 0xff;
        d[c/4] = String.fromCharCode(o1, o2, o3);
        if (h4 == 0x40) {
            d[c/4] = String.fromCharCode(o1, o2);
        }
        if (h3 == 0x40) {
            d[c/4] = String.fromCharCode(o1);
        }
    }
    plain = d.join(''); 
    return utf8decode ? plain.decodeUTF8() : plain; 
}
var Utf8 = {};  
Utf8.encode = function(strUni) {
    var strUtf = strUni.replace(
        /[\u0080-\u07ff]/g,  
        function(c) { 
            var cc = c.charCodeAt(0);
            return String.fromCharCode(0xc0 | cc>>6, 0x80 | cc&0x3f); }
        );
    strUtf = strUtf.replace(
        /[\u0800-\uffff]/g,  
        function(c) { 
            var cc = c.charCodeAt(0); 
            return String.fromCharCode(0xe0 | cc>>12, 0x80 | cc>>6&0x3F, 0x80 | cc&0x3f); }
        );
    return strUtf;
}
Utf8.decode = function(strUtf) {
    var strUni = strUtf.replace(
        /[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g,  
        function(c) { 
            var cc = ((c.charCodeAt(0)&0x0f)<<12) | ((c.charCodeAt(1)&0x3f)<<6) | ( c.charCodeAt(2)&0x3f); 
            return String.fromCharCode(cc); }
        );
    strUni = strUni.replace(
        /[\u00c0-\u00df][\u0080-\u00bf]/g,                
        function(c) {  
            var cc = (c.charCodeAt(0)&0x1f)<<6 | c.charCodeAt(1)&0x3f;
            return String.fromCharCode(cc); }
        );
    return strUni;
}
function Encrypt(str, key){
	return Aes.Ctr.encrypt(str, key, 256);
}
function Decrypt(str, key){
	return Aes.Ctr.decrypt(str, key, 256);
}
//ip = Decrypt(ip, "password", 256);
function go_user_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/users/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자를 삭제하였습니다.");
                    parent.refresh('users');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('users');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/users/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 모두 삭제하였습니다.");
            parent.refresh('users');
            window.close();
        }
    }
}
function user_type_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/user-types/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                $("#userTypeModal .card-title").text("사용자 유형 수정");
                $("#typeUpdateBtn").remove();
                $("#typeInsertBtn").remove();
                 let btn = '<button type="button" id="typeUpdateBtn" onclick="javascript: go_user_type_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='type_form']").append(btn);
                let f = document.type_form;
                f.type_name.value = data.du_type_info.name;
                f.level.value = data.du_type_info.level;
                f.is_admin.value = (data.du_type_info.is_admin) ? "True" : "False";
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function user_update_info(id) {
  
    $('#imgatt').remove();

    $('#cert_chk').prop('checked',true);
    $('#access_time_bez').attr('disabled',false);
    $('#access_time_end').attr('disabled',false);
    $('#access_time_bez').attr('style','');
    $('#access_time_end').attr('style','');
    
    $('#employee_no').attr('disabled',true);
    $('#employee_no').attr('style','background-color:#fff;');

    $("#userModal .card-title").text("사용자 수정");
    let closebtn = '<button type="button" class="close" onclick="javascript:refresh(\'users\');" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
    $(".card-title").append(closebtn);
    // $("#userUpdateBtn").remove();
    // $("#userInsertBtn").remove();
    //  let btn = '<button type="button" id="userUpdateBtn" onclick="javascript: go_user_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
    // $("form[name='user_form']").append(btn);
    $('#userInsertBtn').attr('onclick','javascript:go_user_update2("update",'+id+');');

    $.ajax({
        type : "GET",
        url : ip + "/v1/users/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                console.log(data);
                
                if(data.du_info.secure_info.is_timecard == 1){
                    $("#is_timecard").prop('checked',true);
                }else{
                    $("#is_timecard").prop('checked',false);
                }

                accgroup_device_list();
                //var birthday = data.du_info.private_info.birthday.slice(0,10);
                //console.log(birthday);
                var f = document.user_form;
                //f.user_id.value = data.du_info.id;
                f.user_name.value = data.du_info.name;

                f.home_addr.value = data.du_info.private_info.home_addr;
                f.phone.value = data.du_info.private_info.phone;
                f.email.value = data.du_info.private_info.email;
                f.birthday.value = data.du_info.private_info.birthday;
                f.join_company.value = data.du_info.private_info.join_company;
                f.leave_company.value = data.du_info.private_info.leave_company;
                
                f.group_id.value = data.du_info.du_group.group_id;
                f.job_position_id.value = data.du_info.du_job_position.id;
                f.job_group_id.value = data.du_info.du_job_group.id;
                f.type_id.value = data.du_info.du_type.type_id;
                f.access_id.value = data.du_info.du_access_group.access_id;
                f.gender.value = data.du_info.secure_info.gender;
                //f.cardno_count.value = data.du_info.secure_info.cardno_count;
                //f.cardnos.value = data.du_info.secure_info.cardnos;
                //f.pic_url.value = data.du_info.secure_info.pic_url;
                //f.pic_size.value = data.du_info.secure_info.pic_size;
                f.fic_file.value = data.du_info.secure_info.pic_data;
                //f.finger_size1.value = data.du_info.secure_info.finger_size1;
                //f.finger_data1.value = data.du_info.secure_info.finger_data1;
                //f.finger_size2.value = data.du_info.secure_info.finger_size2;
                //f.finger_data2.value = data.du_info.secure_info.finger_data2;
                //f.is_timecard.value = data.du_info.secure_info.is_timecard;
                //f.timecard_rule_id.value = data.du_info.secure_info.timecard_rule_id;
                //f.use_access_time.value = data.du_info.secure_info.use_access_time;
                f.access_time_bez.value = data.du_info.secure_info.access_time_bez;
                f.access_time_end.value = data.du_info.secure_info.access_time_end;
                
                if(data.du_info.secure_info.cardno_list[0] == "undefined"){
                    data.du_info.secure_info.cardno_list[0] = "";
                }

                f.cardno_list.value = data.du_info.secure_info.cardno_list[0];
                f.employee_no.value = data.du_info.secure_info.employee_no;
                //f.employee_code.value = data.du_info.secure_info.employee_code;
               

                if(data.du_info.secure_info.pic_url != ""){
                document.getElementById('preview').innerHTML = '<img id="imgatt" onclick="javascript:pic_dele();" size="" style="width:200px;height:200px;" src="' + data.du_info.secure_info.pic_url + '">';
                }
            }else if(data.error_code == 8){
                console.log(data);
                alert("업데이트 실패");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            console.log(jqXHR);
            console.log("error");
        }
        
    });
}
// function go_user_update() {
//     var f = document.user_update_form;
//     if (f.user_id.value == "") {
//         alert("ID를 입력해주세요."); f.user_id.focus();
//         return;
//     }else if (f.user_name.value == "") {
//         alert("이름을 입력해주세요."); f.user_name.focus();
//         return;
//     }else if (f.home_addr.value == "") {
//         alert("주소 입력해주세요."); f.home_addr.focus();
//         return;
//     }else if (f.phone.value == "") {
//         alert("연락처를 입력해주세요."); f.phone.focus();
//         return;
//     }else if (f.email.value == "") {
//         alert("이메일을 입력해주세요."); f.email.focus();
//         return;
//     }else if (f.birthday.value == "") {
//         alert("생년월일을 입력해주세요."); f.birthday.focus();
//         return;
//     }else if (f.join_company.value == "") {
//         alert("입사일 입력해주세요."); f.join_company.focus();
//         return;
//     }else if (f.leave_company.value == "") {
//         alert("퇴사일을 입력해주세요."); f.leave_company.focus();
//         return;
//     }else if (f.cardno_count.value == "") {
//         alert("카드개수 입력해주세요."); f.cardno_count.focus();
//         return;
//     }else if (f.cardnos.value == "") {
//         alert("카드번호 목록을 입력해주세요."); f.cardnos.focus();
//         return;
//     }else if (f.pin_num.value == "") {
//         alert("핀번호를 입력해주세요."); f.pin_num.focus();
//         return;
//     }else if (f.pic_url.value == "") {
//         alert("사진주소를 입력해주세요."); f.pic_url.focus();
//         return;
//     }else if (f.pic_size.value == "") {
//         alert("사진 크기를 입력해주세요."); f.pic_size.focus();
//         return;
//     }else if (f.pic_data.value == "") {
//         alert("사진 DATA를 입력해주세요."); f.pic_data.focus();
//         return;
//     }else if (f.finger_size1.value == "") {
//         alert("손가락 크기1을 입력해주세요."); f.finger_size1.focus();
//         return;
//     }else if (f.finger_data1.value == "") {
//         alert("손가락 DATA1을 입력해주세요."); f.finger_data1.focus();
//         return;
//     }else if (f.finger_size2.value == "") {
//         alert("손가락 크기2을 입력해주세요."); f.finger_size2.focus();
//         return;
//     }else if (f.finger_data2.value == "") {
//         alert("손가락 DATA2을 입력해주세요."); f.finger_data2.focus();
//         return;
//     }else if (f.is_timecard.value == "") {
//         alert("is_timecard를 입력해주세요."); f.is_timecard.focus();
//         return;
//     }else if (f.timecard_rule_id.value == "") {
//         alert("timecard_rule_id를 입력해주세요."); f.timecard_rule_id.focus();
//         return;
//     }else if (f.use_access_time.value == "") {
//         alert("인증 가능기간 설정 유무를 입력해주세요."); f.use_access_time.focus();
//         return;
//     }else if (f.access_time_bez.value == "") {
//         alert("인증 시작일을 입력해주세요."); f.access_time_bez.focus();
//         return;
//     }else if (f.access_time_end.value == "") {
//         alert("인증 종료일을 입력해주세요."); f.access_time_end.focus();
//         return;
//     }else if (f.employee_no.value == "") {
//         alert("사원 NO를 입력해주세요."); f.employee_no.focus();
//         return;
//     }else if (f.employee_code.value == "") {
//         alert("사원 코드를 입력해주세요."); f.employee_code.focus();
//         return;
//     }else if (f.reply_to.value == "") {
//         alert("reply_to를 입력해주세요."); f.reply_to.focus();
//         return;
//     }else if (f.reply_method.value == "") {
//         alert("reply_method를 입력해주세요."); f.reply_method.focus();
//         return;
//     }else if (f.reply_msg.value == "") {
//         alert("reply_msg를 입력해주세요."); f.reply_msg.focus();
//         return;
//     }
//     var cardnos = Array();
//     cardnos.push(f.cardnos.value);

//     var du_info = {"id": f.user_id.value, "name": f.user_name.value, "group_id": f.group_id.value, "job_position_id": f.job_position_id.value, "job_group_id": f.job_group_id.value, "access_id": f.access_id.value, "type_id": f.type_id.value,
//                     "private_info": {"home_addr": f.home_addr.value, "phone": f.phone.value, "email": f.email.value, "birthday": f.birthday.value, "join_company": f.join_company.value, "leave_company": f.leave_company.value },
//                     "secure_info": {"cardno_count": f.cardno_count.value, "cardno_list": cardnos, "pin_num": f.pin_num.value, "pic_url" : f.pic_url.value, "pic_size": f.pic_size.value, "pic_data": f.pic_data.value,
//                     "finger_size1": f.finger_size1.value, "finger_data1": f.finger_data1.value, "finger_size2": f.finger_size2.value, "finger_data2": f.finger_data2.value, "is_timecard": f.is_timecard.value,
//                     "timecard_rule_id": f.timecard_rule_id.value, "use_access_time": f.use_access_time.value, "access_time_bez" : f.access_time_bez.value, "access_time_end": f.access_time_end.value, "employee_no": f.employee_no.value,
//                     "employee_code": f.employee_code.value}, "reply_info": {"reply_to": f.reply_to.value, "reply_method": f.reply_method.value, "reply_msg": f.reply_msg.value}};

//     $.ajax({
//         type : "PUT",
//         url : ip + "/v1/users/" + id,
//         dataType : 'json',
//         contentType : 'application/json',
//         data : JSON.stringify(du_info),
//         success : function(data) {
//             //console.log(data);
//             if (data.error_code == 1) {
//                 alert("해당 사용자를 수정하였습니다.");
//                 opener.refresh('users');
//                 window.close();
//             }
//             else{
//                 alert("해당 사용자가 중복되었습니다.");
//             }
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.log(jqXHR);
//             console.log("error");
//         }
//     });
// }
// function go_user_insert() {
//     var f = document.user_form;
// 	if (f.user_name.value == "") {
//         alert("이름을 입력해주세요."); f.user_name.focus();
//         return;
//     }else if (f.home_addr.value == "") {
//         alert("주소 입력해주세요."); f.home_addr.focus();
//         return;
//     }else if (f.phone.value == "") {
//         alert("연락처를 입력해주세요."); f.phone.focus();
//         return;
//     }else if (f.email.value == "") {
//         alert("이메일을 입력해주세요."); f.email.focus();
//         return;
//     }else if (f.birthday.value == "") {
//         alert("생년월일을 입력해주세요."); f.birthday.focus();
//         return;
//     }else if (f.join_company.value == "") {
//         alert("입사일 입력해주세요."); f.join_company.focus();
//         return;
//     }else if (f.leave_company.value == "") {
//         alert("퇴사일을 입력해주세요."); f.leave_company.focus();
//         return;
//     }else if (f.cardno_count.value == "") {
//         alert("카드개수 입력해주세요."); f.cardno_count.focus();
//         return;
//     }else if (f.cardnos.value == "") {
//         alert("카드번호 목록을 입력해주세요."); f.cardnos.focus();
//         return;
//     }else if (f.pin_num.value == "") {
//         alert("핀번호를 입력해주세요."); f.pin_num.focus();
//         return;
//     }else if (f.pic_url.value == "") {
//         alert("사진주소를 입력해주세요."); f.pic_url.focus();
//         return;
//     }else if (f.pic_size.value == "") {
//         alert("사진 크기를 입력해주세요."); f.pic_size.focus();
//         return;
//     }else if (f.pic_data.value == "") {
//         alert("사진 DATA를 입력해주세요."); f.pic_data.focus();
//         return;
//     }else if (f.finger_size1.value == "") {
//         alert("손가락 크기1을 입력해주세요."); f.finger_size1.focus();
//         return;
//     }else if (f.finger_data1.value == "") {
//         alert("손가락 DATA1을 입력해주세요."); f.finger_data1.focus();
//         return;
//     }else if (f.finger_size2.value == "") {
//         alert("손가락 크기2을 입력해주세요."); f.finger_size2.focus();
//         return;
//     }else if (f.finger_data2.value == "") {
//         alert("손가락 DATA2을 입력해주세요."); f.finger_data2.focus();
//         return;
//     }else if (f.is_timecard.value == "") {
//         alert("is_timecard를 입력해주세요."); f.is_timecard.focus();
//         return;
//     }else if (f.timecard_rule_id.value == "") {
//         alert("timecard_rule_id를 입력해주세요."); f.timecard_rule_id.focus();
//         return;
//     }else if (f.use_access_time.value == "") {
//         alert("인증 가능기간 설정 유무를 입력해주세요."); f.use_access_time.focus();
//         return;
//     }else if (f.access_time_bez.value == "") {
//         alert("인증 시작일을 입력해주세요."); f.access_time_bez.focus();
//         return;
//     }else if (f.access_time_end.value == "") {
//         alert("인증 종료일을 입력해주세요."); f.access_time_end.focus();
//         return;
//     }else if (f.employee_no.value == "") {
//         alert("사원 NO를 입력해주세요."); f.employee_no.focus();
//         return;
//     }else if (f.employee_code.value == "") {
//         alert("사원 코드를 입력해주세요."); f.employee_code.focus();
//         return;
//     }else if (f.reply_to.value == "") {
//         alert("reply_to를 입력해주세요."); f.reply_to.focus();
//         return;
//     }else if (f.reply_method.value == "") {
//         alert("reply_method를 입력해주세요."); f.reply_method.focus();
//         return;
//     }else if (f.reply_msg.value == "") {
//         alert("reply_msg를 입력해주세요."); f.reply_msg.focus();
//         return;
//     }
//     var cardnos = Array();
//     cardnos.push(f.cardnos.value);

//    var du_info = { "name": f.user_name.value, "group_id": f.group_id.value, "job_position_id": f.job_position_id.value, "job_group_id": f.job_group_id.value, "access_id": f.access_id.value, "type_id": f.type_id.value,
//                     "private_info": {"home_addr": f.home_addr.value, "phone": f.phone.value, "email": f.email.value, "birthday": f.birthday.value, "join_company": f.join_company.value, "leave_company": f.leave_company.value },
//                     "secure_info": {"cardno_count": f.cardno_count.value, "cardno_list": cardnos, "pin_num": f.pin_num.value, "pic_url" : f.pic_url.value, "pic_size": f.pic_size.value, "pic_data": f.pic_data.value,
//                     "finger_size1": f.finger_size1.value, "finger_data1": f.finger_data1.value, "finger_size2": f.finger_size2.value, "finger_data2": f.finger_data2.value, "is_timecard": f.is_timecard.value,
//                     "timecard_rule_id": f.timecard_rule_id.value, "use_access_time": f.use_access_time.value, "access_time_bez" : f.access_time_bez.value, "access_time_end": f.access_time_end.value, "employee_no": f.employee_no.value,
//                     "employee_code": f.employee_code.value}, "reply_info": {"reply_to": f.reply_to.value, "reply_method": f.reply_method.value, "reply_msg": f.reply_msg.value}};
    
//     $.ajax({
//         type : "POST",
//         url : ip + "/v1/users",
//         dataType : 'json',
//         contentType : 'application/json',
//         data : JSON.stringify(du_info),
//         success : function(data) {
//             console.log(data);
//             if (data.error_code == 1) {
//                 alert("사용자를 추가하였습니다.");
//                 refresh('users');
                
//             }
//             else{
//                 alert("사용자가 중복되었습니다.");
//             }
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.log(jqXHR);
//             console.log("error");
//         }
//     });
// }
function go_user_insert2(job,rs) {
    
    if(job == "add"){

    var f = document.user_form;
	if (f.user_name.value == "") {
        alert("이름을 입력해주세요."); f.user_name.focus();
        return;
    }else if(f.employee_no.value == ""){
        alert("사번을 입력해주세요"); f.employee_no.focus();
        return;
    }
    // else if (f.phone.value == "") {
    //     alert("연락처를 입력해주세요."); f.phone.focus();
    //     return;
    // }else if (f.email.value == "") {
    //     alert("이메일을 입력해주세요."); f.email.focus();
    //     return;
    // }else if(f.job_position_id.value == ""){
    //     alert("직급을 선택해주세요"); f.job_position_id.focus();
    //     return;
    // }else if(f.job_group_id.value == ""){
    //     alert("직군을 선택해주세요"); f.job_group_id.focus();
    //     return;
    // }else if(f.birthday.value == ""){
    //     alert("생년월일을 선택해주세요"); f.birthday.focus();
    //     return;
    // }
    // else if(f.phone.value == ""){
    //     alert("핸드폰 번호를 입력해주세요"); f.phone.focus();
    //     return;
    // }else if(f.home_addr.value == ""){
    //     alert("주소를 입력해주세요"); f.home_addr.focus();
    //     return;
    // }else if(f.gender.value == ""){
    //     alert("성별을 선택해주세요"); f.gender.focus();
    //     return;
    // }else if(f.type_id.value ==""){
    //     alert("사용자 유형을 선택해주세요"); f.type_id.focus();
    //     return;
    // }else if(f.access_id.value == ""){
    //     alert("출입 그룹을 선택해주세요"); f.access_id.focus();
    //     return;
    // }
    if($("#imgatt").length){
   
    var pic_data = $('#imgatt').attr('src');
    var pic_datastr = pic_data.split(',');
    
    var pic_size = $('#imgatt').attr('size');
    var pic_datastring = pic_datastr[1];
   
    }else
    {
    var pic_size = 0;
    var pic_datastring = '';
    }
    if($('#cert_chk').is(":checked") == false){
        alert("인증 유효 기간을 체크해주세요"); f.cert_chk.focus();
        return;
    }
    if($('#cert_chk').is(":checked") == true){
        if(f.access_time_bez.value == ""){
            alert("인증 유효 기간의 시작일을 선택해주세요"); f.access_time_bez.focus();
            return;
        }else if(f.access_time_end.value == ""){
            alert("인증 유효 기간의 종료일을 선택해주세요"); f.access_time_end.focus();
            return;
    }
}

    if(f.join_company.value == ""){
        alert("입사일을 선택하세요"); f.join_company.focus();
        return;
    }
    if(f.leave_company.value == ""){
        f.leave_company.value = "2021-07-07";
    }
    if($('#is_timecard').is(":checked") == true){
        var is_timecard = 1;
    }else{
        var is_timecard = 0;
    }
    var cardnos = Array();
    cardnos.push(f.cardno_list.value);
    //console.log(pic_datastring);
    //console.log(pic_size);
    //$("body").append('<iframe id="subscribe" src="../phpMQTT/subscribe2.php" width="500" height="0"></iframe>');

   var du_info = { "name": f.user_name.value, "group_id": parseInt(f.group_id.value), "job_position_id": parseInt(f.job_position_id.value), "job_group_id": parseInt(f.job_group_id.value), "access_id": parseInt(f.access_id.value),"type_id": parseInt(f.type_id.value),
                    "private_info": {"home_addr": f.home_addr.value, "phone": f.phone.value, "email": f.email.value, "birthday": f.birthday.value, "join_company": f.join_company.value, "leave_company": f.leave_company.value },
                    "secure_info": {"gender": f.gender.value,"cardno_count": 1, "cardno_list": cardnos, "pin_num": 'test', "pic_url" : 'test', "pic_size": pic_size, "pic_data": pic_datastring,
                    "finger_size1": 1, "finger_data1": 'test', "finger_size2": 1, "finger_data2": 'test', "is_timecard": is_timecard,
                    "timecard_rule_id": 1, "use_access_time": 1, "access_time_bez" : f.access_time_bez.value, "access_time_end": f.access_time_end.value, "employee_no": parseInt(f.employee_no.value),
                    "employee_code": 'test123'}, "reply_info": {"reply_to": 'users/users', "reply_method": 'MQTT', "reply_msg": 'AddUsers'}};
                    console.log(du_info);
            
    $.ajax({
        type : "POST",
        url : ip + "/v1/users",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
        
                alert("사용자를 추가하였습니다.");
                refresh('users');
            }
            else if(data.error_code == 2){
                alert("데이터 베이스 연결 실패.");
            }else if(data.error_code == 3){
                alert("데이터 삽입 실패");
            }else if(data.error_code == 4){
                alert("데이터 삭제 실패");
            }else if(data.error_code == 5){
                alert("데이터 업데이트 실패");
            }else if(data.error_code == 6){
                alert("데이터 가져오기 실패");
            }else if(data.error_code == 7){
                alert( "시간(날짜) 형식이 잘못 되었습니다");
            }else if(data.error_code == 8){
                alert( "데이터가 존재 하지않습니다");
            }else if(data.error_code == 9){
                alert( "삭제할 수 없는 데이터입니다");
            }else if(data.error_code == 11){
                alert( "시간이 중복되었습니다");
            }else if(data.error_code == 12){
                alert( "경로가 잘못 되었습니다");
            }else if(data.error_code == 13){
                alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
            }else if(data.error_code == 14){
                alert( "깊이의 최대값(4)을 초과 하였습니다"); 
            }else if(data.error_code == 15){
                alert( "이미지 저장을 실패하였습니다");
            }else if(data.error_code == 100){
                alert( "작업 완료");
            }else if(data.error_code == 101){
                alert( "작업 실패");
            }
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
else
    {
        let data = JSON.parse(rs);
        console.log(data);

            var progress_cur = data.reply_info.progress_cur;

        if(data.reply_info.progress_cur != '100'){
           
            var progress_cur2 = String(progress_cur).substr(0,2);
            $(parent.document).find('#progress_cur'+data.reply_data.user_simple_info.id+'').text("동기화 중("+progress_cur2+"%)");
            

        }else{
            $(parent.document).find('#progress_cur'+data.reply_data.user_simple_info.id+'').text("동기화 완료");

        }
        
    }

}
function go_user_update2(job,id) {
    if(job == "update"){
    var f = document.user_form;
	if (f.user_name.value == "") {
        alert("이름을 입력해주세요."); f.user_name.focus();
        return;
    }else if (f.phone.value == "") {
        alert("연락처를 입력해주세요."); f.phone.focus();
        return;
    }else if (f.email.value == "") {
        alert("이메일을 입력해주세요."); f.email.focus();
        return;
    }
    
    if($('#imgatt').attr('src').length > 120){
   
        var pic_data = $('#imgatt').attr('src');
        var pic_datastr = pic_data.split(',');
        
        var pic_size = $('#imgatt').attr('size');
        var pic_datastring = pic_datastr[1];
        
        }else{
            pic_datastring = $('#imgatt').attr('src');
        }
    
    if(f.join_company.value == ""){
        alert("입사일을 선택하세요"); f.join_company.focus();
        return;
    }
    if(f.leave_company.value == ""){
        f.leave_company.value = "2021-07-07";
    }
    if(f.birthday.value == ""){
        alert("생년월일을 선택하세요"); f.birthday.value.focus();
        return;
    }
    if($('#cert_chk').is(":checked") == false){
        alert("인증 유효 기간을 체크해주세요"); f.cert_chk.focus();
        return;
    }
    if($('#cert_chk').is(":checked") == true){
        if(f.access_time_bez.value == ""){
            alert("인증 유효 기간의 시작일을 선택해주세요"); f.access_time_bez.focus();
            return;
        }else if(f.access_time_end.value == ""){
            alert("인증 유효 기간의 종료일을 선택해주세요"); f.access_time_end.focus();
            return;
    }
}

if($('#is_timecard').is(":checked") == true){
    var is_timecard = 1;
}else{
    var is_timecard = 0;
}
    var cardnos = Array();
    cardnos.push(f.cardno_list.value);
    

    if(pic_datastring.length > 120){
    var du_info = { "name": f.user_name.value, "group_id": parseInt(f.group_id.value), "job_position_id": parseInt(f.job_position_id.value), "job_group_id": parseInt(f.job_group_id.value), "access_id": parseInt(f.access_id.value),"type_id": parseInt(f.type_id.value),
                    "private_info": {"home_addr": f.home_addr.value, "phone": f.phone.value, "email": f.email.value, "birthday": f.birthday.value, "join_company": f.join_company.value, "leave_company": f.leave_company.value },
                    "secure_info": {"cardno_count": 1, "cardno_list": cardnos, "pin_num": 'test', "pic_url" : "", "pic_size": pic_size, "pic_data": pic_datastring,
                    "finger_size1": 1, "finger_data1": 'test', "finger_size2": 1, "finger_data2": 'test', "is_timecard": is_timecard,
                    "timecard_rule_id": 1, "use_access_time": 1, "access_time_bez" : f.access_time_bez.value, "access_time_end": f.access_time_end.value, "employee_no": parseInt(f.employee_no.value),
                    "employee_code": 'test123'}, "reply_info": {"reply_to": 'users/users', "reply_method": 'MQTT', "reply_msg": 'AddUsers'}};
    }else{
        var du_info = { "name": f.user_name.value, "group_id": parseInt(f.group_id.value), "job_position_id": parseInt(f.job_position_id.value), "job_group_id": parseInt(f.job_group_id.value), "access_id": parseInt(f.access_id.value),"type_id": parseInt(f.type_id.value),
        "private_info": {"home_addr": f.home_addr.value, "phone": f.phone.value, "email": f.email.value, "birthday": f.birthday.value, "join_company": f.join_company.value, "leave_company": f.leave_company.value },
        "secure_info": {"cardno_count": 1, "cardno_list": cardnos, "pin_num": 'test', "pic_url" : pic_datastring, "pic_size": "", "pic_data": "",
        "finger_size1": 1, "finger_data1": 'test', "finger_size2": 1, "finger_data2": 'test', "is_timecard": is_timecard,
        "timecard_rule_id": 1, "use_access_time": 1, "access_time_bez" : f.access_time_bez.value, "access_time_end": f.access_time_end.value, "employee_no": parseInt(f.employee_no.value),
        "employee_code": 'test123'}, "reply_info": {"reply_to": 'users/users', "reply_method": 'MQTT', "reply_msg": 'AddUsers'}};
    }

                    console.log(du_info);
    $.ajax({
        type : "PUT",
        url : ip + "/v1/users/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("사용자를 수정하였습니다.");
                refresh('users');
            }
            else if(data.error_code == 2){
                alert("데이터 베이스 연결 실패.");
            }else if(data.error_code == 3){
                alert("데이터 삽입 실패");
            }else if(data.error_code == 4){
                alert("데이터 삭제 실패");
            }else if(data.error_code == 5){
                alert("데이터 업데이트 실패");
            }else if(data.error_code == 6){
                alert("데이터 가져오기 실패");
            }else if(data.error_code == 7){
                alert( "시간(날짜) 형식이 잘못 되었습니다");
            }else if(data.error_code == 8){
                alert( "데이터가 존재 하지않습니다");
            }else if(data.error_code == 9){
                alert( "삭제할 수 없는 데이터입니다");
            }else if(data.error_code == 11){
                alert( "시간이 중복되었습니다");
            }else if(data.error_code == 12){
                alert( "경로가 잘못 되었습니다");
            }else if(data.error_code == 13){
                alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
            }else if(data.error_code == 14){
                alert( "깊이의 최대값(4)을 초과 하였습니다"); 
            }else if(data.error_code == 15){
                alert( "이미지 저장을 실패하였습니다");
            }else if(data.error_code == 100){
                alert( "작업 완료");
            }else if(data.error_code == 101){
                alert( "작업 실패");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
// }else if(job == "scan"){
//     let data = JSON.parse(rs);
//     console.log(data);

//         var progress_cur = data.reply_info.progress_cur;

//     if(data.reply_info.progress_cur != '100'){
       
//         var progress_cur2 = String(progress_cur).substr(0,2);
//         $(parent.document).find('#progress_cur'+data.reply_data.user_simple_info.id+'').text("동기화 중("+progress_cur2+"%)");
        

//     }else{
//         $(parent.document).find('#progress_cur'+data.reply_data.user_simple_info.id+'').text("동기화 완료");

//     }


// }
}
}
function go_server_user_permit_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/server/user-rights/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 서버 사용자 권한을 삭제하였습니다.");
                    parent.refresh('server-user-permit');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('server-user-permit');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/server/user-rights/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 서버 사용자 권한을 모두 삭제하였습니다.");
            parent.refresh('server-user-permit');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 서버 사용자 권한을 일부만 삭제하였습니다.");
            parent.refresh('server-user-permit');
            window.close();
        }
        else {
            alert("선택된 서버 사용자 권한을 삭제하지 못했습니다.");
            parent.refresh('server-user-permit');
            window.close();
        }
    }
}
function server_user_permit_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/server/user-rights/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                $("#serverUserPermitModal .card-title").text("서버 사용자 권한 수정");
                $("#serverUserPermitUpdateBtn").remove();
                $("#serverUserPermitInsertBtn").remove();
                   var btn = '<button type="button" id="serverUserPermitUpdateBtn" onclick="javascript: go_server_user_permit_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='server_user_permit_form']").append(btn);

                let f = document.server_user_permit_form;
                f.permit_name.value = data.permit_info.name;
                f.major.value = data.permit_info.major;
                f.minor.value = data.permit_info.minor;
            }
            else{
                alert("해당 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function go_server_user_permit_update(id) {
    var f = document.server_user_permit_form;
    if (f.permit_name.value == "") {
        alert("서버 사용자 권한 이름을 입력해주세요."); f.permit_name.focus();
        return;
    }else if (f.major.value == "") {
        alert("Major를 입력해주세요."); f.major.focus();
        return;
    }else if (f.minor.value == "") {
        alert("Minor를 입력해주세요."); f.minor.focus();
        return;
    }
    var permit_info = {"name": f.permit_name.value, "major": f.major.value, "minor": f.minor.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/server/user-rights/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(permit_info),
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                alert("서버 사용자 권한을 수정하였습니다.");
                refresh('server-user-permit');
            }
            else{
                alert("서버 사용자 권한이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function go_server_user_permit_insert() {
    var f = document.server_user_permit_form;
    if (f.permit_name.value == "") {
        alert("서버 사용자 권한 이름을 입력해주세요."); f.permit_name.focus();
        return;
    }else if (f.major.value == "") {
        alert("Major를 입력해주세요."); f.major.focus();
        return;
    }else if (f.minor.value == "") {
        alert("Minor를 입력해주세요."); f.minor.focus();
        return;
    }
    var permit_info = {"name": f.permit_name.value, "major": f.major.value, "minor": f.minor.value};
    $.ajax({
        type : "POST",
        url : ip + "/v1/server/user-rights",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(permit_info),
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                alert("서버 사용자 권한을 추가하였습니다.");
                refresh('server-user-permit');
            }
            else{
                alert("서버 사용자 권한이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function server_users_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/server/users/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 서버 사용자를 삭제하였습니다.");
                    parent.refresh('server-users');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('server-users');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/server/users/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 서버 사용자를 모두 삭제하였습니다.");
            parent.refresh('server-users');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 서버 사용자를 일부만 삭제하였습니다.");
            parent.refresh('server-users');
            window.close();
        }
        else {
            alert("선택된 서버 사용자를 삭제하지 못했습니다.");
            parent.refresh('server-users');
            window.close();
        }
    }
}
function server_user_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/server/users/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                $("#serverUserModal .card-title").text("서버 사용자 수정");
                $("#serverUserUpdateBtn").remove();
                $("#serverUserInsertBtn").remove();
                 var btn = '<button type="button" id="serverUserUpdateBtn" onclick="javascript: go_server_user_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='server_user_form']").append(btn);

                $("#login_id").attr("readonly","readonly");
                let f = document.server_user_form;
                f.server_user_name.value = data.user_info.name;
                f.login_id.value = data.user_info.login_id;
                f.login_pw.value = data.user_info.login_pw;
                f.type_id.value = data.user_info.user_type.id;
                f.permit_id.value = data.user_info.user_permit.id;
               
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
    
}
function go_server_user_update(id) {
    var f = document.server_user_form;
    if (f.server_user_name.value == "") {
        alert("서버 사용자 이름을 입력해주세요."); f.server_user_name.focus();
        return;
    }else if (f.login_pw.value == "") {
        alert("Password를 입력해주세요."); f.login_pw.focus();
        return;
    }
    var user_info = {"name": f.server_user_name.value, "login_pw": f.login_pw.value, "type_id" : f.type_id.value, "permit_id" : f.permit_id.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/server/users/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(user_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("서버 사용자를 수정하였습니다.");
                refresh('server-users');
            }
            else{
                alert("서버 사용자가 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function go_server_user_insert() {
    var f = document.server_user_form;
    if (f.server_user_name.value == "") {
        alert("서버 사용자 이름을 입력해주세요."); f.server_user_name.focus();
        return;
    }else if (f.login_id.value == "") {
        alert("ID를 입력해주세요."); f.login_id.focus();
        return;
    }else if (f.login_pw.value == "") {
        alert("Password를 입력해주세요."); f.login_pw.focus();
        return;
    }

    var user_info = {"name": f.server_user_name.value, "login_id": f.login_id.value, "login_pw": f.login_pw.value, "type_id" : f.type_id.value, "permit_id" : f.permit_id.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/server/users",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(user_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("서버 사용자를 추가하였습니다.");
                refresh('server-users');
            }
            else{
                alert("서버 사용자가 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function server_user_type_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/server/user-types/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 유형을 삭제하였습니다.");
                    parent.refresh('server-user-type');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('server-user-type');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/server/user-types/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 유형을 모두 삭제하였습니다.");
            parent.refresh('server-user-type');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 유형을 일부만 삭제하였습니다.");
            parent.refresh('server-user-type');
            window.close();
        }
        else {
            alert("선택된 사용자 유형을 삭제하지 못했습니다.");
            parent.refresh('server-user-type');
            window.close();
        }
    }
}
function go_server_user_type_update(id) {
    var f = document.server_user_type_form;
    if (f.type_name.value == "") {
        alert("서버 사용자 유형 이름을 입력해주세요."); f.type_name.focus();
        return;
    }
    
    var type_info = {"name": f.type_name.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/server/user-types/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(type_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 유형을 수정하였습니다.");
                refresh('server-user-type');
            }
            else{
                alert("사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function server_user_type_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/server/user-types/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                $("#serverUserTypeModal .card-title").text("서버 사용자 유형 수정");
                $("#serverUserTypeUpdateBtn").remove();
                $("#serverUserTypeInsertBtn").remove();
                 var btn = '<button type="button" id="serverUserTypeUpdateBtn" onclick="javascript: go_server_user_type_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='server_user_type_form']").append(btn);

                let f = document.server_user_type_form;
                f.type_name.value = data.type_info.name;
            }
            else{
                alert("해당 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_server_user_type_insert() {
    var f = document.server_user_type_form;
    if (f.type_name.value == "") {
        alert("서버 사용자 유형 이름을 입력해주세요."); f.type_name.focus();
        return;
    }

    var type_info = {"name": f.type_name.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/server/user-types",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(type_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("서버 사용자 유형을 추가하였습니다.");
                refresh('server-user-type');
            }
            else{
                alert("서버 사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_acc_group_update(id) {
    var f = document.access_insert_form;
    if (f.access_name.value == "") {
        alert("출입 그룹 이름을 입력해주세요."); f.access_name.focus();
        return;
    }else if (f.bez_date.value == "") {
        alert("출입 그룹 시작 시간을 입력해주세요."); f.bez_date.focus();
        return;
    }else if (f.end_date.value == "") {
        alert("출입 그룹 종료 시간을 입력해주세요."); f.end_date.focus();
        return;
    }
   
    var arr_device_ids = new Array();
    var device_ids = document.getElementsByName("device_ids");
    var device_ids_cnt = device_ids.length;
    for (let i = 0; i < device_ids_cnt; i++) {
        if (device_ids[i].checked == true) {
            arr_device_ids.push(device_ids[i].value);
        }
    }
    var acc_group_info = {"name": f.access_name.value, "bez_date": f.bez_date.value, "end_date": f.end_date.value, "device_ids" : arr_device_ids, "acc_time_group_id": f.acc_time_group_id.value };
    console.log(acc_group_info);
    $.ajax({
        type : "PUT",
        url : ip + "/v1/access-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(acc_group_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("출입 그룹을 수정하였습니다.");
                refresh('access-group');
            
            }
            else if(data.error_code == 2){
                alert("데이터 베이스 연결 실패.");
            }else if(data.error_code == 3){
                alert("데이터 삽입 실패");
            }else if(data.error_code == 4){
                alert("데이터 삭제 실패");
            }else if(data.error_code == 5){
                alert("데이터 업데이트 실패");
            }else if(data.error_code == 6){
                alert("데이터 가져오기 실패");
            }else if(data.error_code == 7){
                alert( "시간(날짜) 형식이 잘못 되었습니다");
            }else if(data.error_code == 8){
                alert( "데이터가 존재 하지않습니다");
            }else if(data.error_code == 9){
                alert( "삭제할 수 없는 데이터입니다");
            }else if(data.error_code == 11){
                alert( "시간이 중복되었습니다");
            }else if(data.error_code == 12){
                alert( "경로가 잘못 되었습니다");
            }else if(data.error_code == 13){
                alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
            }else if(data.error_code == 14){
                alert( "깊이의 최대값(4)을 초과 하였습니다"); 
            }else if(data.error_code == 15){
                alert( "이미지 저장을 실패하였습니다");
            }else if(data.error_code == 100){
                alert( "작업 완료");
            }else if(data.error_code == 101){
                alert( "작업 실패");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function access_group_delete() {
    
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/access-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                //console.log(data);
                if (data.error_code == 1) {
                    alert("해당 출입 그룹을 삭제하였습니다.");
                    parent.refresh('access-group');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('access-group');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/access-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 출입 그룹을 모두 삭제하였습니다.");
            parent.refresh('access-group');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 출입 그룹을 일부만 삭제하였습니다.");
            parent.refresh('access-group');
            window.close();
        }
        else {
            alert("선택된 출입 그룹을 삭제하지 못했습니다.");
            parent.refresh('access-group');
            window.close();
        }
    }
}

function acc_group_update_info() {
    var device_ids = new Array();
    var acc_time_group_id;

    $.ajax({
        type : "GET",
        url : ip + "/v1/access-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            $("#access_name").val(data.access_group_info.name);
            $("#bez_date").val(data.access_group_info.bez_date);
            $("#end_date").val(data.access_group_info.end_date);
            device_ids = data.access_group_info.device_ids;
            acc_time_group_id = data.access_group_info.acc_time_group_id;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });

    $.ajax({
        type : "GET",
        url : ip + "/v1/devices",
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                let totalRows = data.device_count;
                for (let i = 0; i < totalRows; i++) {
                    let str_checked = (device_ids.includes(String(data.device_infos[i].id))) ? "checked" : "";
                    let str = '<label style="margin-right: 2rem;"><input type="checkbox" name="device_ids" style="width: 20px; height: 12px;" value="'+data.device_infos[i].id+'" '+str_checked+'><span>'+data.device_infos[i].product_info.model_name+'</span></label>';
                    $("#device-ids").append(str);
                }
            }
            else{
                console.log("5-1-5. /v1/devices (err)");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });

    $.ajax({
        type : "GET",
        url : ip + "/v1/access-time-groups",
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            let totalRows = data.access_time_group_infos.length;
            for (let i = 0; i < totalRows; i++) {
                let str_selected = (acc_time_group_id == data.access_time_group_infos[i].id) ? "selected" : "";
                let str = '<option value="'+data.access_time_group_infos[i].id+'" '+str_selected+'>'+data.access_time_group_infos[i].name+'</option>';
                $("#acc_time_group_id").append(str);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_acc_group_insert(page) {
    var f = document.access_insert_form;
    if (f.access_name.value == "") {
        alert("출입 그룹 이름을 입력해주세요."); f.access_name.focus();
        return;
    }else if (f.bez_date.value == "") {
        alert("출입 그룹 시작 시간을 입력해주세요."); f.bez_date.focus();
        return;
    }else if (f.end_date.value == "") {
        alert("출입 그룹 종료 시간을 입력해주세요."); f.end_date.focus();
        return;
    }

    var arr_device_ids = new Array();
    var device_ids = document.getElementsByName("device_ids");
    //console.log(document.getElementsByName("device_ids"));
    var device_ids_cnt = device_ids.length;
    for (let i = 0; i < device_ids_cnt; i++) {
        if (device_ids[i].checked == true) {
            arr_device_ids.push(device_ids[i].value);
        }
    }
    //console.log(arr_device_ids);
    var acc_group_info = {"name": f.access_name.value, "bez_date": f.bez_date.value, "end_date": f.end_date.value, "device_ids" : arr_device_ids, "acc_time_group_id": f.acc_time_group_id.value };
    //console.log(acc_group_info);
    $.ajax({
        type : "POST",
        url : ip + "/v1/access-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(acc_group_info),
        success : function(data) {
            console.log(data);
            
            if (data.error_code == 1) {
                alert("출입 그룹을 추가하였습니다.");
               
                if(page == 'users'){
                    $('#theModal').modal('hide');
                    
                    $.ajax({
                        type:"GET",
                        url : ip + "/v1/access-groups",
                        dataType : 'json',
                        contentType : 'application/json',
                        success : function(data){
                           
                            console.log(data);
                          
                              var f = document.access_insert_form;
                                
                                var accessgroupStr = "";
                                for(i=0; i<data.access_group_infos.length; i++){
                                //var selected = (f.access_name.value == data.access_group_infos[i].name) ? 'selected' : '';
                                accessgroupStr += "<option value='"+data.access_group_infos[i].id+"'>"+data.access_group_infos[i].name+"</option>";
                                }
                                $("#access_id").empty();
                                $("#access_id").append(accessgroupStr);
                                
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log("error");
                        }
                    });










                }else{
                    refresh('access-group');
                }

            }
            else if(data.error_code == 2){
                alert("데이터 베이스 연결 실패.");
            }else if(data.error_code == 3){
                alert("데이터 삽입 실패");
            }else if(data.error_code == 4){
                alert("데이터 삭제 실패");
            }else if(data.error_code == 5){
                alert("데이터 업데이트 실패");
            }else if(data.error_code == 6){
                alert("데이터 가져오기 실패");
            }else if(data.error_code == 7){
                alert( "시간(날짜) 형식이 잘못 되었습니다");
            }else if(data.error_code == 8){
                alert( "데이터가 존재 하지않습니다");
            }else if(data.error_code == 9){
                alert( "삭제할 수 없는 데이터입니다");
            }else if(data.error_code == 11){
                alert( "시간이 중복되었습니다");
            }else if(data.error_code == 12){
                alert( "경로가 잘못 되었습니다");
            }else if(data.error_code == 13){
                alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
            }else if(data.error_code == 14){
                alert( "깊이의 최대값(4)을 초과 하였습니다"); 
            }else if(data.error_code == 15){
                alert( "이미지 저장을 실패하였습니다");
            }else if(data.error_code == 100){
                alert( "작업 완료");
            }else if(data.error_code == 101){
                alert( "작업 실패");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_user_type_update(id) {
    var f = document.type_form;
    if (f.type_name.value == "") {
        alert("Type Name을 입력해주세요."); f.type_name.focus();
        return;
    }else if (f.level.value == "") {
        alert("Level을 입력해주세요."); f.level.focus();
        return;
    }
    
    var du_type_info = {"name": f.type_name.value, "level": f.level.value, "is_admin": f.is_admin.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/user-types/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_type_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 유형을 수정하였습니다.");
                refresh('user-type');
            }
            else{
                alert("사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_user_type_insert() {
    var f = document.type_form;
    if (f.type_name.value == "") {
        alert("Type Name을 입력해주세요."); f.type_name.focus();
        return;
    }else if (f.level.value == "") {
        alert("Level을 입력해주세요."); f.level.focus();
        return;
    }

    var du_type_info = {"name": f.type_name.value, "level": f.level.value, "is_admin": f.is_admin.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/user-types",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_type_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 유형을 추가하였습니다.");
                //refresh('user-type');
                $('#userTypeModal').modal('hide');

                $.ajax({
                    type:"GET",
                    url : ip + "/v1/user-types",
                    dataType : 'json',
                    contentType : 'application/json',
                    success : function(data){
                       
                        console.log(data);
                      
                          var f = document.type_form;
                            
                            var user_typeStr = "";
                            for(i=0; i<data.du_type_infos.length; i++){
                            var selected = (f.type_name.value == data.du_type_infos[i].name) ? 'selected' : '';
                            user_typeStr += "<option value='"+data.du_type_infos[i].id+"' "+selected+">"+data.du_type_infos[i].name+"</option>";
                            }
                            $("#type_id").empty();
                            $("#type_id").append(user_typeStr);
                            
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            }
            else{
                alert("사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_type_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/user-types/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 유형을 삭제하였습니다.");
                    parent.refresh('user-type');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('user-type');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/user-types/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 유형을 모두 삭제하였습니다.");
            parent.refresh('user-type');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 유형을 일부만 삭제하였습니다.");
            parent.refresh('user-type');
            window.close();
        }
        else {
            alert("선택된 사용자 유형을 삭제하지 못했습니다.");
            parent.refresh('user-type');
            window.close();
        }
    }
}

function go_user_job_position_insert() {
    var f = document.job_positions_form;
    if (f.job_positions_name.value == "") {
        alert("Job Position Name을 입력해주세요."); f.job_positions_name.focus();
        return;
    }

    var du_job_position_info = {"name": f.job_positions_name.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/job-positions",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_job_position_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 직급을 추가하였습니다.");
                //refresh('users');
                $('#userJopPositionModal').modal('hide');
            
                $.ajax({
                    type:"GET",
                    url : ip + "/v1/job-positions",
                    dataType : 'json',
                    contentType : 'application/json',
                    success : function(data){
                       
                        console.log(data);
                      
                          var f = document.job_positions_form;
                            
                            var job_positionStr = "";
                            for(i=0; i<data.du_job_position_infos.length; i++){
                            var selected = (f.job_positions_name.value == data.du_job_position_infos[i].name) ? 'selected' : '';
                            job_positionStr += "<option value='"+data.du_job_position_infos[i].id+"' "+selected+">"+data.du_job_position_infos[i].name+"</option>";
                            }
                            $("#job_position_id").empty();
                            $("#job_position_id").append(job_positionStr);
                            
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            }
            else{
                alert("사용자 직급이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_job_position_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/job-positions/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                let f = document.job_positions_form;
                f.job_positions_name.value = data.du_job_position_info.name;

                $("#userJopPositionModal .card-title").text("사용자 직급 수정");
                $("#jobPositionUpdateBtn").remove();
                $("#jobPositionInsertBtn").remove();
                var btn = '<button type="button" id="jobPositionUpdateBtn" onclick="javascript: go_user_job_position_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='job_positions_form']").append(btn);
            }
            else{
                alert("해당 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_user_job_position_update(id) {
    var f = document.job_positions_form;
    if (f.job_positions_name.value == "") {
        alert("Job Position Name을 입력해주세요."); f.job_positions_name.focus();
        return;
    }
    
    var du_job_position_info = {"name": f.job_positions_name.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/job-positions/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_job_position_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 유형을 수정하였습니다.");
                refresh('user-job-positions');
            }
            else{
                alert("사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_job_position_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/job-positions/" + id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 직급을 삭제하였습니다.");
                    parent.refresh('user-job-positions');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/job-positions/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 직급을 모두 삭제하였습니다.");
            parent.refresh('user-job-positions');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 직급을 일부만 삭제하였습니다.");
            parent.refresh('user-job-positions');
            window.close();
        }
        else {
            alert("선택된 사용자 직급을 삭제하지 못했습니다.");
            parent.refresh('user-job-positions');
            window.close();
        }
    }
}

function go_user_job_group_update(id) {
    var f = document.job_groups_form;
    if (f.job_groups_name.value == "") {
        alert("Job Groups Name을 입력해주세요."); f.job_groups_name.focus();
        return;
    }
    
    var du_job_group_info = {"name": f.job_groups_name.value};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/job-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_job_group_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 유형을 수정하였습니다.");
                refresh('user-job-groups');
            }
            else{
                alert("사용자 유형이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_job_group_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/job-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                var f  = document.job_groups_form;
                f.job_groups_name.value = data.du_job_group_info.name;

                $("#userJobGroupModal .card-title").text("사용자 직군 수정");
                $("#jobGroupUpdateBtn").remove();
                $("#jobGroupInsertBtn").remove();
                var btn = '<button type="button" id="jobGroupUpdateBtn" onclick="javascript: go_user_job_group_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='job_groups_form']").append(btn);
            }
            else{
                alert("해당 직급을 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_user_job_group_insert() {
    var f = document.job_groups_form;
    if (f.job_groups_name.value == "") {
        alert("직군을 입력해주세요."); f.job_groups_name.focus();
        return;
    }

    var du_job_group_info = {"name": f.job_groups_name.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/job-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_job_group_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 직군을 추가하였습니다.");
                //refresh('users');
                $('#userJobGroupModal').modal('hide');


                $.ajax({
                    type:"GET",
                    url : ip + "/v1/job-groups",
                    dataType : 'json',
                    contentType : 'application/json',
                    success : function(data){
                       
                        console.log(data);
                      
                          var f = document.job_groups_form;
                            
                            var job_groupStr = "";
                            for(i=0; i<data.du_job_group_infos.length; i++){
                            var selected = (f.job_groups_name.value == data.du_job_group_infos[i].name) ? 'selected' : '';
                            job_groupStr += "<option value='"+data.du_job_group_infos[i].id+"' "+selected+">"+data.du_job_group_infos[i].name+"</option>";
                            }
                            $("#job_group_id").empty();
                            $("#job_group_id").append(job_groupStr);
                            
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            }
            else{
                alert("사용자 직군이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_job_group_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/job-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 직군을 삭제하였습니다.");
                    parent.refresh('user-job-groups');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/job-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 직군을 모두 삭제하였습니다.");
            parent.refresh('user-job-groups');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 직군을 일부만 삭제하였습니다.");
            parent.refresh('user-job-groups');
            window.close();
        }
        else {
            alert("선택된 사용자 직군을 삭제하지 못했습니다.");
            parent.refresh('user-job-groups');
            window.close();
        }
    }
}

function go_user_group_update(id) {
    var f = document.user_group_form;
    if (f.group_name.value == "") {
        alert("Group Name을 입력해주세요."); f.group_name.focus();
        return;
    }
    var parent_id = f.parent_id.value;
    if (id == f.parent_id.value) {
        parent_id = 0;
    }
    
    var du_groupinfo = {"name": f.group_name.value, "parent_id": parent_id};
    $.ajax({
        type : "PUT",
        url : ip + "/v1/user-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_groupinfo),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 그룹을 수정하였습니다.");
                refresh('user-groups');
            }
            else{
                alert("사용자 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_group_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/user-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                var f = document.user_group_form;
                f.group_name.value = data.du_group_info.name;
                f.parent_id.value = data.du_group_info.parent_id;

                $("#userGroupModal .card-title").text("사용자 그룹 수정");
                $("#userGroupUpdateBtn").remove();
                $("#userGroupInsertBtn").remove();
                var btn = '<button type="button" id="userGroupUpdateBtn" onclick="javascript: go_user_group_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='user_group_form']").append(btn);
            }
            else{
                alert("해당 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_user_group_insert() {
    var f = document.user_group_form;
    if (f.group_name.value == "") {
        alert("Group Name을 입력해주세요."); f.group_name.focus();
        return;
    }
    

    var du_groupinfo = {"name": f.group_name.value, "parent_id": f.parent_id.value};
    
    $.ajax({
        type : "POST",
        url : ip + "/v1/user-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_groupinfo),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 그룹을 추가하였습니다.");
                refresh('user-groups');
            }
            else{
                alert("사용자 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_group_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/user-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 그룹을 삭제하였습니다.");
                    parent.refresh('user-groups');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/user-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 그룹을 모두 삭제하였습니다.");
            parent.refresh('user-groups');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 그룹을 일부만 삭제하였습니다.");
            parent.refresh('user-groups');
            window.close();
        }
        else {
            alert("선택된 사용자 그룹을 삭제하지 못했습니다.");
            parent.refresh('user-groups');
            window.close();
        }
    }
}
function go_device_update(id) {
    var f = document.device_update_form;
    var sync = $("#time_sync").is(":checked");
    if (f.deviceName.value == "") {
        alert("단말기 이름을 입력해주세요."); f.deviceName.focus();
        return;
    }else if (f.account_id.value == "") {
        alert("관제 ID를 입력해주세요."); f.account_id.focus();
        return;
    }else if (f.account_pw.value == "") {
        alert("관제 PW를 입력해주세요."); f.account_pw.focus();
        return;
    }else if (f.ip_addr.value == "") {
        alert("IP 주소를 입력해주세요."); f.ip_addr.focus();
        return;
    }else if (f.tcp_port.value == "") {
        alert("TCP Port를 입력해주세요."); f.tcp_port.focus();
        return;
    }else if (f.web_port.value == "") {
        alert("Web Port를 입력해주세요."); f.web_port.focus();
        return;
    }else if (f.serial_no.value == "") {
        alert("시리얼 번호를 입력해주세요."); f.serial_no.focus();
        return;
    }
    // else if (f.link_name.value == "") {
    //     alert("카메라 이름을 입력해주세요."); f.link_name.focus();
    //     return;
    // }else if (f.link_rtsp_url.value == "") {
    //     alert("카메라 RTSP URL을 입력해주세요."); f.link_rtsp_url.focus();
    //     return;
    // }else if (f.link_uid.value == "") {
    //     alert("카메라 UID를 입력해주세요."); f.link_uid.focus();
    //     return;
    // }else if (f.link_password.value == "") {
    //     alert("카메라 비밀번호를 입력해주세요."); f.link_password.focus();
    //     return;
    // }

    // var device_infos = {"name": f.name.value, "group_id": f.group_id.value, "event_arming_type": f.event_arming_type.value, "rtsp_url": f.rtsp_url.value, "door_count": f.door_count.value, 
    //                     "product_info": {"product_code": f.product_code.value, "serial_no": f.serial_no.value, "serial_no_ex": f.serial_no_ex.value, "account_id": f.account_id.value, "account_pw": f.account_pw.value, "type_code": f.type_code.value },
    //                     "device_net_info": {"ip_addr": f.ip_addr.value, "tcp_port": f.tcp_port.value, "web_port": f.web_port.value, "mac_addr": f.mac_addr.value },
    //                     "device_status": {"is_master": f.is_master.value, "is_active": f.is_active.value, "is_connected": f.is_connected.value, "is_twoway_audio": f.is_twoway_audio.value, "is_event_arming": f.is_event_arming.value },
    //                     "link_device": {"link_name": f.link_name.value, "link_rtsp_url": f.link_rtsp_url.value, "link_ip": f.link_ip.value, "link_port": f.link_port.value, "link_uid": f.link_uid.value, "link_password": f.link_password.value }};

    var device_infos = {"name": f.deviceName.value, "group_id": f.group_id.value, "product_info": {"account_id": f.account_id.value, "account_pw": f.account_pw.value, "model_name": f.model_name.value, "serial_no": f.serial_no.value },
                        "device_net_info": {"ip_addr": f.ip_addr.value, "tcp_port": f.tcp_port.value, "web_port": f.web_port.value },
                        "link_device": {"link_name": f.link_name.value, "link_rtsp_url": f.link_rtsp_url.value, "link_uid": f.link_uid.value, "link_password": f.link_password.value }};

    $.ajax({
        type : "PUT",
        url : ip + "/v1/devices/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(device_infos),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("단말기를 수정하였습니다.");
                refresh('devices');
            }
            else{
                alert("단말기가 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}


function go_device_scan_insert_info(ip, http_port, web_port, model_name, serial_no) {
    var f = document.device_insert_form;
    f.ip_addr.value = ip;
    f.tcp_port.value = http_port;
    f.web_port.value = web_port;
    f.model_name.value = model_name;
    f.serial_no.value = serial_no;
}

function device_update_info(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/devices/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                console.log(data);
                var f = document.device_update_form;
                f.group_id.value = data.device_info.device_group.group_id;
                f.deviceName.value = data.device_info.name;
                f.ip_addr.value = data.device_info.device_net_info.ip_addr;
                f.tcp_port.value = data.device_info.device_net_info.tcp_port;
                f.web_port.value = data.device_info.device_net_info.web_port;
                f.account_id.value = data.device_info.product_info.account_id;
                f.account_pw.value = data.device_info.product_info.account_pw;
                f.model_name.value = data.device_info.product_info.model_name;
                f.serial_no.value = data.device_info.product_info.serial_no;
                //f.type_code.value = data.device_info.product_info.type_code;
                f.link_name.value = data.device_info.link_device.link_name;
                f.link_rtsp_url.value = data.device_info.link_device.rtsp_url;
                f.link_uid.value = data.device_info.link_device.link_uid;
                f.link_password.value = data.device_info.link_device.link_password;
                //$("#device_update_time_sync").attr("checked","checked");
                $("#deviceUpdateBtn").attr("onclick","javascript: go_device_update('"+id+"');");
            }
            else{
                alert("해당 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_device_insert() {
    var f = document.device_insert_form;
    var sync = $("#time_sync").is(":checked");
    if (f.deviceName.value == "") {
        alert("단말기 이름을 입력해주세요."); f.deviceName.focus();
        return;
    }else if (f.account_id.value == "") {
        alert("관제 ID를 입력해주세요."); f.account_id.focus();
        return;
    }else if (f.account_pw.value == "") {
        alert("관제 PW를 입력해주세요."); f.account_pw.focus();
        return;
    }else if (f.ip_addr.value == "") {
        alert("IP 주소를 입력해주세요."); f.ip_addr.focus();
        return;
    }else if (f.tcp_port.value == "") {
        alert("TCP Port를 입력해주세요."); f.tcp_port.focus();
        return;
    }else if (f.web_port.value == "") {
        alert("Web Port를 입력해주세요."); f.web_port.focus();
        return;
    }else if (f.serial_no.value == "") {
        alert("시리얼 번호를 입력해주세요."); f.serial_no.focus();
        return;
    }
    // else if (f.link_name.value == "") {
    //     alert("카메라 이름을 입력해주세요."); f.link_name.focus();
    //     return;
    // }else if (f.link_rtsp_url.value == "") {
    //     alert("카메라 RTSP URL을 입력해주세요."); f.link_rtsp_url.focus();
    //     return;
    // }else if (f.link_uid.value == "") {
    //     alert("카메라 UID를 입력해주세요."); f.link_uid.focus();
    //     return;
    // }else if (f.link_password.value == "") {
    //     alert("카메라 비밀번호를 입력해주세요."); f.link_password.focus();
    //     return;
    // }

    // var device_infos = {"name": f.name.value, "group_id": f.group_id.value, "event_arming_type": f.event_arming_type.value, "rtsp_url": f.rtsp_url.value, "door_count": f.door_count.value, 
    //                     "product_info": {"product_code": f.product_code.value, "serial_no": f.serial_no.value, "serial_no_ex": f.serial_no_ex.value, "account_id": f.account_id.value, "account_pw": f.account_pw.value, "type_code": f.type_code.value },
    //                     "device_net_info": {"ip_addr": f.ip_addr.value, "tcp_port": f.tcp_port.value, "web_port": f.web_port.value, "mac_addr": f.mac_addr.value },
    //                     "device_status": {"is_master": f.is_master.value, "is_active": f.is_active.value, "is_connected": f.is_connected.value, "is_twoway_audio": f.is_twoway_audio.value, "is_event_arming": f.is_event_arming.value },
    //                     "link_device": {"link_name": f.link_name.value, "link_rtsp_url": f.link_rtsp_url.value, "link_ip": f.link_ip.value, "link_port": f.link_port.value, "link_uid": f.link_uid.value, "link_password": f.link_password.value }};

    var device_infos = {"name": f.deviceName.value, "group_id": f.group_id.value, "product_info": {"account_id": f.account_id.value, "account_pw": f.account_pw.value, "model_name": f.model_name.value, "serial_no": f.serial_no.value },
                        "device_net_info": {"ip_addr": f.ip_addr.value, "tcp_port": f.tcp_port.value, "web_port": f.web_port.value },
                        "link_device": {"link_name": f.link_name.value, "link_rtsp_url": f.link_rtsp_url.value, "link_uid": f.link_uid.value, "link_password": f.link_password.value }};

    $.ajax({
        type : "POST",
        url : ip + "/v1/devices",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(device_infos),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("단말기를 추가하였습니다.");
                refresh("devices");
                
            }
            else{
                alert("단말기가 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function device_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/devices/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 단말기를 삭제하였습니다.");
                    parent.refresh('devices');
                    window.close();
                }
                else{
                    alert("해당 단말기를 삭제하지 못했습니다.");
                    parent.refresh('devices');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {

            $.ajax({
                type : "DELETE",
                url : ip + "/v1/devices/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 단말기를 모두 삭제하였습니다.");
            parent.refresh('devices');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 단말기를 일부만 삭제하였습니다.");
            parent.refresh('devices');
            window.close();
        }
        else {
            alert("선택된 단말기를 삭제하지 못했습니다.");
            parent.refresh('devices');
            window.close();
        }
    }
}

function go_device_group_update(id) {
    var pattern_spc = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
    var f = document.deviceGroupForm;
    if (f.groupName.value == "") {
        alert("그룹 이름을 입력해주세요."); f.groupName.focus();
        return;
    } else if(f.parentId.value == "") {
        alert("부모 그룹을 선택해주세요."); f.parentId.focus();
        return;
    }
    if(pattern_spc.test(f.groupName.value)){
        alert("특수문자가 입력되었습니다");
        return;
    }
    var du_groupinfo = {"name": f.groupName.value, "parent_id": f.parentId.value};

    $.ajax({
        type : "PUT",
        url : ip + "/v1/device-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_groupinfo),
        success : function(data) {
            if (data.error_code == 1) {
                alert("단말기 그룹을 수정하였습니다.");
                refresh('devices');
            }
            else{
                alert("단말기 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function device_group_update_info(id, job) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/device-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                if (job == "device-groups") {
                    var f = document.group_update_form;
                    f.group_name.value = data.d_group_info.name;
                    f.parent_id.value  = data.d_group_info.parent_id;
                }else if(job == "devices") {
                    var f = document.deviceGroupForm;
                    f.groupName.value = data.d_group_info.name;
                    f.parentId.value  = data.d_group_info.parent_id;
                }
            }
            else{
                alert("해당 단말기 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_device_group_insert(job) {
    var pattern_spc = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi // 특수문자

    if (job == 'devices' || job == "simpleDeviceGroup") {
        var f = document.deviceGroupForm;
        //console.log(f.parentId.value);
        if (f.groupName.value == "") {
            alert("그룹 이름을 입력해주세요."); f.groupName.focus();
            return;
        }
        if(pattern_spc.test(f.groupName.value)){
            alert("특수문자가 입력되었습니다");
            return;
        }
        var du_group_info = {"name": f.groupName.value, "parent_id": f.parentId.value};
    } else {
        var f = document.group_insert_form;
        if (f.group_name.value == "") {
            alert("그룹 이름을 입력해주세요."); f.group_name.focus();
            return;
        }
        var du_group_info = {"name": f.group_name.value, "parent_id": f.parent_id.value};
    }

    $.ajax({
        type : "POST",
        url : ip + "/v1/device-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_group_info),
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                alert("단말기 그룹이 추가되었습니다.");
                if (job == 'devices') {
                    refresh('devices');
                } else if (job == "simpleDeviceGroup"){
                    $.ajax({
                        type : "GET",
                        url : ip + "/v1/device-groups",
                        dataType : 'json',
                        contentType : 'application/json',
                        success : function(data) {
                         
                            console.log(data);
                            if (data.error_code == 1) {
                                let totalRows = data.d_group_infos.length;
                               
                                deviceGroupsStr = "<option value='0'>최상위</option>";
                                for (i=0; i < totalRows; i++) { 
                                    let selected = (f.groupName.value == data.d_group_infos[i].name ) ? 'selected' : '';
                                    deviceGroupsStr += "<option value='"+data.d_group_infos[i].id+"' "+selected+">"+data.d_group_infos[i].name+"</option>";
                                }
                                $("#deviceInsertGroupId").empty();
                                $("#deviceInsertGroupId").append(deviceGroupsStr);
                                $("#deviceUpdateGroupId").empty();
                                $("#deviceUpdateGroupId").append(deviceGroupsStr);
                                $("#deviceGroupModal h4 button").trigger("click");
                                f.groupName.value = "";
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log("error");
                        }
                    });
                } else {
                    refresh('devices');
                }
            }
            else{
                alert("사용자 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function device_group_delete(job, arr) {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/device-groups/" + arr,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                   alert("해당 단말기 그룹을 삭제하였습니다.");
                   refresh('devices');
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    } else if(job == "devices") {
        
        var j = 0;
        for (var i = 0; i <= arr.length; i++) {
            (function(i) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/device-groups/" + $("#"+arr[i]).attr("deviceid"),
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
                })
            })(i);
        }
        if (i == j) {
            alert("선택된 단말기 그룹을 모두 삭제하였습니다.");
            refresh('devices');
        }
        else if (j > 0) {
            
            alert("선택된 단말기 그룹을 일부만 삭제하였습니다.");
            refresh('devices');
        }
        else {
            alert("선택된 단말기 그룹을 삭제하지 못했습니다.");
            refresh('devices');
        }
    } else {
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/device-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 단말기 그룹을 모두 삭제하였습니다.");
            parent.refresh('device-groups');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 단말기 그룹을 일부만 삭제하였습니다.");
            parent.refresh('device-groups');
            window.close();
        }
        else {
            alert("선택된 단말기 그룹을 삭제하지 못했습니다.");
            parent.refresh('device-groups');
            window.close();
        }
    }
}

function acc_week_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/access-weeks/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 주간 일정을 삭제하였습니다.");
                    parent.refresh('access-week');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('access-week');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/access-weeks/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 주간 일정을 모두 삭제하였습니다.");
            parent.refresh('access-week');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 주간 일정을 일부만 삭제하였습니다.");
            parent.refresh('access-week');
            window.close();
        }
        else {
            alert("선택된 주간 일정을 삭제하지 못했습니다.");
            parent.refresh('access-week');
            window.close();
        }
    }
}

function go_acc_week_insert() {
    var f = document.acc_week_insert_form;

    if (f.access_week_name.value == "") {
        alert("출입 시간 그룹 이름을 입력해주세요."); f.access_week_name.focus();
        return;
    }
    var mon_time_1 = (Number(f.mon_time_1_start.value) << 16) + Number(f.mon_time_1_end.value);
    var mon_time_2 = (Number(f.mon_time_2_start.value) << 16) + Number(f.mon_time_2_end.value);
//    var mon_time_3 = (Number(f.mon_time_3_start.value) << 16) + Number(f.mon_time_3_end.value);
//    var mon_time_4 = (Number(f.mon_time_4_start.value) << 16) + Number(f.mon_time_4_end.value);
    var tue_time_1 = (Number(f.tue_time_1_start.value) << 16) + Number(f.tue_time_1_end.value);
    var tue_time_2 = (Number(f.tue_time_2_start.value) << 16) + Number(f.tue_time_2_end.value);
//    var tue_time_3 = (Number(f.tue_time_3_start.value) << 16) + Number(f.tue_time_3_end.value);
//    var tue_time_4 = (Number(f.tue_time_4_start.value) << 16) + Number(f.tue_time_4_end.value);
    var wed_time_1 = (Number(f.wed_time_1_start.value) << 16) + Number(f.wed_time_1_end.value);
    var wed_time_2 = (Number(f.wed_time_2_start.value) << 16) + Number(f.wed_time_2_end.value);
//    var wed_time_3 = (Number(f.wed_time_3_start.value) << 16) + Number(f.wed_time_3_end.value);
//    var wed_time_4 = (Number(f.wed_time_4_start.value) << 16) + Number(f.wed_time_4_end.value);
    var thu_time_1 = (Number(f.thu_time_1_start.value) << 16) + Number(f.thu_time_1_end.value);
    var thu_time_2 = (Number(f.thu_time_2_start.value) << 16) + Number(f.thu_time_2_end.value);
//    var thu_time_3 = (Number(f.thu_time_3_start.value) << 16) + Number(f.thu_time_3_end.value);
//    var thu_time_4 = (Number(f.thu_time_4_start.value) << 16) + Number(f.thu_time_4_end.value);
    var fri_time_1 = (Number(f.fri_time_1_start.value) << 16) + Number(f.fri_time_1_end.value);
    var fri_time_2 = (Number(f.fri_time_2_start.value) << 16) + Number(f.fri_time_2_end.value);
//    var fri_time_3 = (Number(f.fri_time_3_start.value) << 16) + Number(f.fri_time_3_end.value);
//    var fri_time_4 = (Number(f.fri_time_4_start.value) << 16) + Number(f.fri_time_4_end.value);
    var sat_time_1 = (Number(f.sat_time_1_start.value) << 16) + Number(f.sat_time_1_end.value);
    var sat_time_2 = (Number(f.sat_time_2_start.value) << 16) + Number(f.sat_time_2_end.value);
//    var sat_time_3 = (Number(f.sat_time_3_start.value) << 16) + Number(f.sat_time_3_end.value);
//    var sat_time_4 = (Number(f.sat_time_4_start.value) << 16) + Number(f.sat_time_4_end.value);
    var sun_time_1 = (Number(f.sun_time_1_start.value) << 16) + Number(f.sun_time_1_end.value);
    var sun_time_2 = (Number(f.sun_time_2_start.value) << 16) + Number(f.sun_time_2_end.value);
//    var sun_time_3 = (Number(f.sun_time_3_start.value) << 16) + Number(f.sun_time_3_end.value);
//    var sun_time_4 = (Number(f.sun_time_4_start.value) << 16) + Number(f.sun_time_4_end.value);


    //var access_week_info = {"name":f.access_week_name.value,"mon_time_1": mon_time_1,"mon_time_2": mon_time_2,"mon_time_3": mon_time_3,"mon_time_4": mon_time_4,"tue_time_1": tue_time_1,"tue_time_2": tue_time_2,"tue_time_3": tue_time_3,"tue_time_4": tue_time_4,"wed_time_1": wed_time_1,"wed_time_2": wed_time_2,"wed_time_3": wed_time_3,"wed_time_4": wed_time_4,"thu_time_1": thu_time_1,"thu_time_2": thu_time_2,"thu_time_3": thu_time_3,"thu_time_4": thu_time_4,"fri_time_1": fri_time_1,"fri_time_2": fri_time_2,"fri_time_3": fri_time_3,"fri_time_4": fri_time_4,"sat_time_1": sat_time_1,"sat_time_2": sat_time_2,"sat_time_3": sat_time_3,"sat_time_4": sat_time_4,"sun_time_1": sun_time_1,"sun_time_2": sun_time_2,"sun_time_3": sun_time_3,"sun_time_4": sun_time_4};
    var access_week_info = {"name":f.access_week_name.value,"mon_time_1": mon_time_1,"mon_time_2": mon_time_2,"tue_time_1": tue_time_1,"tue_time_2": tue_time_2,"wed_time_1": wed_time_1,"wed_time_2": wed_time_2,"thu_time_1": thu_time_1,"thu_time_2": thu_time_2,"fri_time_1": fri_time_1,"fri_time_2": fri_time_2,"sat_time_1": sat_time_1,"sat_time_2": sat_time_2,"sun_time_1": sun_time_1,"sun_time_2": sun_time_2};

    $.ajax({
        type : "POST",
        url : ip + "/v1/access-weeks",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(access_week_info),
        success : function(data) {
            if (data.error_code == 1) {
                alert("주간 일정을 추가하였습니다.");
                refresh('access-time-group');
            }
            else{
                alert("주간 일정이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_acc_week_update(id) {
    var f = document.acc_week_insert_form;

    if (f.access_week_name.value == "") {
        alert("출입 시간 그룹 이름을 입력해주세요."); f.access_week_name.focus();
        return;
    }
    let mon_time_1 = (Number(f.mon_time_1_start.value) << 16) + (Number(f.mon_time_1_end.value) << 16);
    let mon_time_2 = (Number(f.mon_time_2_start.value) << 16) + (Number(f.mon_time_2_end.value) << 16);
    let mon_time_3 = (Number(f.mon_time_3_start.value) << 16) + (Number(f.mon_time_3_end.value) << 16);
    let mon_time_4 = (Number(f.mon_time_4_start.value) << 16) + (Number(f.mon_time_4_end.value) << 16);
    let tue_time_1 = (Number(f.tue_time_1_start.value) << 16) + (Number(f.tue_time_1_end.value) << 16);
    let tue_time_2 = (Number(f.tue_time_2_start.value) << 16) + (Number(f.tue_time_2_end.value) << 16);
    let tue_time_3 = (Number(f.tue_time_3_start.value) << 16) + (Number(f.tue_time_3_end.value) << 16);
    let tue_time_4 = (Number(f.tue_time_4_start.value) << 16) + (Number(f.tue_time_4_end.value) << 16);
    let wed_time_1 = (Number(f.wed_time_1_start.value) << 16) + (Number(f.wed_time_1_end.value) << 16);
    let wed_time_2 = (Number(f.wed_time_2_start.value) << 16) + (Number(f.wed_time_2_end.value) << 16);
    let wed_time_3 = (Number(f.wed_time_3_start.value) << 16) + (Number(f.wed_time_3_end.value) << 16);
    let wed_time_4 = (Number(f.wed_time_4_start.value) << 16) + (Number(f.wed_time_4_end.value) << 16);
    let thu_time_1 = (Number(f.thu_time_1_start.value) << 16) + (Number(f.thu_time_1_end.value) << 16);
    let thu_time_2 = (Number(f.thu_time_2_start.value) << 16) + (Number(f.thu_time_2_end.value) << 16);
    let thu_time_3 = (Number(f.thu_time_3_start.value) << 16) + (Number(f.thu_time_3_end.value) << 16);
    let thu_time_4 = (Number(f.thu_time_4_start.value) << 16) + (Number(f.thu_time_4_end.value) << 16);
    let fri_time_1 = (Number(f.fri_time_1_start.value) << 16) + (Number(f.fri_time_1_end.value) << 16);
    let fri_time_2 = (Number(f.fri_time_2_start.value) << 16) + (Number(f.fri_time_2_end.value) << 16);
    let fri_time_3 = (Number(f.fri_time_3_start.value) << 16) + (Number(f.fri_time_3_end.value) << 16);
    let fri_time_4 = (Number(f.fri_time_4_start.value) << 16) + (Number(f.fri_time_4_end.value) << 16);
    let sat_time_1 = (Number(f.sat_time_1_start.value) << 16) + (Number(f.sat_time_1_end.value) << 16);
    let sat_time_2 = (Number(f.sat_time_2_start.value) << 16) + (Number(f.sat_time_2_end.value) << 16);
    let sat_time_3 = (Number(f.sat_time_3_start.value) << 16) + (Number(f.sat_time_3_end.value) << 16);
    let sat_time_4 = (Number(f.sat_time_4_start.value) << 16) + (Number(f.sat_time_4_end.value) << 16);
    let sun_time_1 = (Number(f.sun_time_1_start.value) << 16) + (Number(f.sun_time_1_end.value) << 16);
    let sun_time_2 = (Number(f.sun_time_2_start.value) << 16) + (Number(f.sun_time_2_end.value) << 16);
    let sun_time_3 = (Number(f.sun_time_3_start.value) << 16) + (Number(f.sun_time_3_end.value) << 16);
    let sun_time_4 = (Number(f.sun_time_4_start.value) << 16) + (Number(f.sun_time_4_end.value) << 16);


    let access_week_info = {"name":f.access_week_name.value,"mon_time_1": mon_time_1,"mon_time_2": mon_time_2,"mon_time_3": mon_time_3,"mon_time_4": mon_time_4,"tue_time_1": tue_time_1,"tue_time_2": tue_time_2,"tue_time_3": tue_time_3,"tue_time_4": tue_time_4,"wed_time_1": wed_time_1,"wed_time_2": wed_time_2,"wed_time_3": wed_time_3,"wed_time_4": wed_time_4,"thu_time_1": thu_time_1,"thu_time_2": thu_time_2,"thu_time_3": thu_time_3,"thu_time_4": thu_time_4,"fri_time_1": fri_time_1,"fri_time_2": fri_time_2,"fri_time_3": fri_time_3,"fri_time_4": fri_time_4,"sat_time_1": sat_time_1,"sat_time_2": sat_time_2,"sat_time_3": sat_time_3,"sat_time_4": sat_time_4,"sun_time_1": sun_time_1,"sun_time_2": sun_time_2,"sun_time_3": sun_time_3,"sun_time_4": sun_time_4};
    console.log(access_week_info);
    $.ajax({
        type : "PUT",
        url : ip + "/v1/access-weeks/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(access_week_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("주간 일정을 수정하였습니다.");
                //opener.refresh('access-week');
                refresh('access-week');
                window.close();
            }
            else{
                alert("주간 일정이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function acc_time_group_delete() {
   
    if (job == '1') {
        
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/access-time-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                
                if (data.error_code == 1) {
                    alert("해당 출입 시간 그룹을 삭제하였습니다.");
                    parent.refresh('access-time-group');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('access-time-group');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/access-time-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 출입 시간 그룹을 모두 삭제하였습니다.");
            parent.refresh('access-time-group');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 출입 시간 그룹을 일부만 삭제하였습니다.");
            parent.refresh('access-time-group');
            window.close();
        }
        else {
            alert("선택된 출입 시간 그룹을 삭제하지 못했습니다.");
            parent.refresh('access-time-group');
            window.close();
        }
    }
}

// function go_acc_time_group_insert() {
  
//     var accTimeGroupForm = document.acc_time_group_insert_form;
//     var accWeekForm  = document.acc_week_insert_form;
    
//     if (accTimeGroupForm.acc_time_group_name.value == "") {
//         alert("출입 시간 그룹 이름을 입력해주세요."); accTimeGroupForm.acc_time_group_name.focus();
//         return;
//     }
//     var num = 0;
//     var dayOfWeek = "월";
//     var accWeekInsertForm = document.acc_week_insert_form;
//     var day = $("form[name=acc_week_insert_form] mon input");
//     for (let i = 0; i < day.length; i++) {
//      if (i == 0) {
//             for (let j = 2; j < day.length; j++) {
//                 console.log(Number(day[i].value.replace(":","")) + " : " + Number(day[j].value.replace(":","")) + " : " + Number(day[i+1].value.replace(":","")));
//                 if (day[i] != "" && day[i+1] != "") {
//                     if (Number(day[i].value.replace(":","")) < Number(day[j].value.replace(":","")) && Number(day[j].value.replace(":","")) < Number(day[i+1].value.replace(":",""))) {
//                         alert(dayOfWeek+"요일 시간범위가 겹쳤습니다.1");
//                         day[j].focus();
//                         return;
//                     }
//                 }
//             }
//         }
//         else if (i%2 == 0) {
//             for (let j = 0; j < day.length; j++) {
//                 if (i != j && i+1 != j) {
//                     if (day[i] != "" && day[i+1] != "") {
//                         if (Number(day[i].value.replace(":","")) < Number(day[j].value.replace(":","")) && Number(day[j].value.replace(":","")) < Number(day[i+1].value.replace(":",""))) {
//                             alert(dayOfWeek+"요일 시간범위가 겹쳤습니다.2");
//                             day[j].focus();
//                             return;
//                         }
//                     }
//                 }
//             }
//         }
//         if (i == day.length-1) {
//             if (num == 0) {day = $("form[name=acc_week_insert_form] #tue .timepicker"); dayOfWeek="화";}
//             else if (num == 1) {day = $("form[name=acc_week_insert_form] #wed input"); dayOfWeek="수";}
//             else if (num == 2) {day = $("form[name=acc_week_insert_form] #thu input"); dayOfWeek="목";}
//             else if (num == 3) {day = $("form[name=acc_week_insert_form] #fri input"); dayOfWeek="금";}
//             else if (num == 4) {day = $("form[name=acc_week_insert_form] #sat input"); dayOfWeek="토";}
//             else if (num == 5) {day = $("form[name=acc_week_insert_form] #sun input"); dayOfWeek="일";}
//             if (num < 6) {i = 0; num++;}
//         }
//     }

//     var cfg = $("form[name=acc_holiday_group_form] #hol_cfg_id1 input[type='time']");
//     console.log(cfg);
//     for (let i = 0; i < cfg.length; i++) {
//         if (i == 0) {
//             for (let j = 2; j < cfg.length; j++) {
//                 //console.log(Number(cfg[i].value.replace(":","")) + " : " + Number(cfg[j].value.replace(":","")) + " : " + Number(cfg[i+1].value.replace(":","")));
//                 if (cfg[i] != "" && cfg[i+1] != "") {
//                     if (Number(cfg[i].value.replace(":","")) < Number(cfg[j].value.replace(":","")) && Number(cfg[j].value.replace(":","")) < Number(cfg[i+1].value.replace(":",""))) {
//                         alert((i+1)+"번째 시간 범위가 겹쳤습니다.1");
//                         cfg[j].focus();
//                         return;
//                     }
//                 }
//             }
//         }
//         else if (i%2 == 0) {
//             console.log("test1");
//             for (let j = 0; j < cfg.length; j++) {
//                 console.log("test1");
//                 if (i != j && i+1 != j) {
//                     console.log("test2");
//                     if (cfg[i] != "" && cfg[i+1] != "") {
//                         console.log(Number(cfg[i].value.replace(":","")) + " : " + Number(cfg[j].value.replace(":","")) + " : " + Number(cfg[i+1].value.replace(":","")));
//                         if (Number(cfg[i].value.replace(":","")) < Number(cfg[j].value.replace(":","")) && Number(cfg[j].value.replace(":","")) < Number(cfg[i+1].value.replace(":",""))) {
//                             alert((i+1)+"번째 시간 범위가 겹쳤습니다.2");
//                             cfg[j].focus();
//                             return;
//                         }
//                     }
//                 }
//             }
//         }
//         cfg = $("form[name=acc_holiday_group_form] #hol_cfg_id"+i+" input[type='time']");
//         i = 0;
//     }
// return;
//     if (accWeekInsertForm.access_week_name.value == "") {
//         alert("출입 시간 그룹 이름을 입력해주세요."); accWeekInsertForm.access_week_name.focus();
//         return;
//     }

//     var mon_time_1 = (Number(f.mon_time_1_start.value) << 16) + Number(f.mon_time_1_end.value);
//     var mon_time_2 = (Number(f.mon_time_2_start.value) << 16) + Number(f.mon_time_2_end.value);
//     var mon_time_3 = (Number(f.mon_time_3_start.value) << 16) + Number(f.mon_time_3_end.value);
//     var mon_time_4 = (Number(f.mon_time_4_start.value) << 16) + Number(f.mon_time_4_end.value);
//     var tue_time_1 = (Number(f.tue_time_1_start.value) << 16) + Number(f.tue_time_1_end.value);
//     var tue_time_2 = (Number(f.tue_time_2_start.value) << 16) + Number(f.tue_time_2_end.value);
//     var tue_time_3 = (Number(f.tue_time_3_start.value) << 16) + Number(f.tue_time_3_end.value);
//     var tue_time_4 = (Number(f.tue_time_4_start.value) << 16) + Number(f.tue_time_4_end.value);
//     var wed_time_1 = (Number(f.wed_time_1_start.value) << 16) + Number(f.wed_time_1_end.value);
//     var wed_time_2 = (Number(f.wed_time_2_start.value) << 16) + Number(f.wed_time_2_end.value);
//     var wed_time_3 = (Number(f.wed_time_3_start.value) << 16) + Number(f.wed_time_3_end.value);
//     var wed_time_4 = (Number(f.wed_time_4_start.value) << 16) + Number(f.wed_time_4_end.value);
//     var thu_time_1 = (Number(f.thu_time_1_start.value) << 16) + Number(f.thu_time_1_end.value);
//     var thu_time_2 = (Number(f.thu_time_2_start.value) << 16) + Number(f.thu_time_2_end.value);
//     var thu_time_3 = (Number(f.thu_time_3_start.value) << 16) + Number(f.thu_time_3_end.value);
//     var thu_time_4 = (Number(f.thu_time_4_start.value) << 16) + Number(f.thu_time_4_end.value);
//     var fri_time_1 = (Number(f.fri_time_1_start.value) << 16) + Number(f.fri_time_1_end.value);
//     var fri_time_2 = (Number(f.fri_time_2_start.value) << 16) + Number(f.fri_time_2_end.value);
//     var fri_time_3 = (Number(f.fri_time_3_start.value) << 16) + Number(f.fri_time_3_end.value);
//     var fri_time_4 = (Number(f.fri_time_4_start.value) << 16) + Number(f.fri_time_4_end.value);
//     var sat_time_1 = (Number(f.sat_time_1_start.value) << 16) + Number(f.sat_time_1_end.value);
//     var sat_time_2 = (Number(f.sat_time_2_start.value) << 16) + Number(f.sat_time_2_end.value);
//     var sat_time_3 = (Number(f.sat_time_3_start.value) << 16) + Number(f.sat_time_3_end.value);
//     var sat_time_4 = (Number(f.sat_time_4_start.value) << 16) + Number(f.sat_time_4_end.value);
//     var sun_time_1 = (Number(f.sun_time_1_start.value) << 16) + Number(f.sun_time_1_end.value);
//     var sun_time_2 = (Number(f.sun_time_2_start.value) << 16) + Number(f.sun_time_2_end.value);
//     var sun_time_3 = (Number(f.sun_time_3_start.value) << 16) + Number(f.sun_time_3_end.value);
//     var sun_time_4 = (Number(f.sun_time_4_start.value) << 16) + Number(f.sun_time_4_end.value);
    
//     var access_time_group_info = {"name": f.access_time_group_name.value, "is_default": f.is_default.value, "acc_week_id": f.acc_week_id.value, "acc_holiday_group_id": f.acc_holiday_group_id.value};
//     console.log(access_time_group_info);
//     $.ajax({
//         type : "POST",
//         url : ip + "/v1/access-time-groups",
//         dataType : 'json',
//         contentType : 'application/json',
//         data : JSON.stringify(access_time_group_info),
//         success : function(data) {
//             if (data.error_code == 1) {
//                 alert("출입 시간 그룹을 추가하였습니다.");
//                 refresh('access-time-group');
//             }
//             else{
//                 alert("출입 시간 그룹이 중복되었습니다.");
//             }
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.log(jqXHR);
//             console.log("error");
//         }
//     });
// }
function go_acc_time_group_insert2(){
    var accTimeGroupForm = document.acc_time_group_insert_form;
    var accWeekForm  = document.acc_week_insert_form;
    var acchoilForm = document.acc_holiday_group_form;
    

    if(accTimeGroupForm.acc_time_group_name.value == ""){
        alert("출입 시간 그룹 이름을 입력하세요");
        accTimeGroupForm.acc_time_group_name.focus();
        return;
    }
    //주간 일정 시간범위
    let week_mon = Array();
    let week_tue = Array();
    let week_wed = Array();
    let week_thu = Array();
    let week_fri = Array();
    let week_sat = Array();
    let week_sun = Array();

    console.log($('mon_time_1_start').val);
    for(i=1; i<=4; i++){

    week_mon[i] = (Number($(`#mon_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#mon_time_${i}_end`).val().replace(":",""));
    week_tue[i] = (Number($(`#tue_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#tue_time_${i}_end`).val().replace(":",""));
    week_wed[i] = (Number($(`#wed_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#wed_time_${i}_end`).val().replace(":",""));
    week_thu[i] = (Number($(`#thu_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#thu_time_${i}_end`).val().replace(":",""));
    week_fri[i] = (Number($(`#fri_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#fri_time_${i}_end`).val().replace(":",""));
    week_sat[i] = (Number($(`#sat_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#sat_time_${i}_end`).val().replace(":",""));
    week_sun[i] = (Number($(`#sun_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#sun_time_${i}_end`).val().replace(":",""));
    alert(week_mon[i]);
    
   
    }

    //휴일 그룹 시간범위
    let holi_1 = Array();
    let holi_2 = Array();
    let holi_3 = Array();
    let holi_4 = Array();
    let holi_5 = Array();
    let holi_6 = Array();
    let holi_7 = Array();
    let holi_8 = Array();
    let holi_9 = Array();
    let holi_10 = Array();
    let holi_11 = Array();
    let holi_12 = Array();
    let holi_13 = Array();
    let holi_14 = Array();
    let holi_15 = Array();
    let holi_16 = Array();
    

    for(i=1; i<=4; i++){
        holi_1[i] = (Number($(`#hol_cfg1_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg1_end${i}`).val().replace(":",""));
        holi_2[i] = (Number($(`#hol_cfg2_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg2_end${i}`).val().replace(":",""));
        holi_3[i] = (Number($(`#hol_cfg3_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg3_end${i}`).val().replace(":",""));
        holi_4[i] = (Number($(`#hol_cfg4_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg4_end${i}`).val().replace(":",""));
        holi_5[i] = (Number($(`#hol_cfg5_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg5_end${i}`).val().replace(":",""));
        holi_6[i] = (Number($(`#hol_cfg6_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg6_end${i}`).val().replace(":",""));
        holi_7[i] = (Number($(`#hol_cfg7_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg7_end${i}`).val().replace(":",""));
        holi_8[i] = (Number($(`#hol_cfg8_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg8_end${i}`).val().replace(":",""));
        holi_9[i] = (Number($(`#hol_cfg9_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg9_end${i}`).val().replace(":",""));
        holi_10[i] = (Number($(`#hol_cfg10_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg10_end${i}`).val().replace(":",""));
        holi_11[i] = (Number($(`#hol_cfg11_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg11_end${i}`).val().replace(":",""));
        holi_12[i] = (Number($(`#hol_cfg12_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg12_end${i}`).val().replace(":",""));
        holi_13[i] = (Number($(`#hol_cfg13_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg13_end${i}`).val().replace(":",""));
        holi_14[i] = (Number($(`#hol_cfg14_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg14_end${i}`).val().replace(":",""));
        holi_15[i] = (Number($(`#hol_cfg15_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg15_end${i}`).val().replace(":",""));
        holi_16[i] = (Number($(`#hol_cfg16_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg16_end${i}`).val().replace(":",""));
        
        }
        if (acchoilForm.hol_cfg1_name.value == "") {
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                
                               }                    
           }
           
        }

        
        if (acchoilForm.hol_cfg1_name.value != "") {
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]}
                               }                    
           }
           
        }
        if(acchoilForm.hol_cfg2_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]}
                               }                    
           }
           
        }
        if(acchoilForm.hol_cfg3_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg4_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg5_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg6_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg7_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]}

                               }                    
           }
        }
        if(acchoilForm.hol_cfg8_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg9_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg10_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg11_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg12_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg13_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_1":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                              "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg14_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                 "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                 "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                 "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                 "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg15_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                 "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                 "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                 "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                 "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]},
                                 "hol_cfg_id15":{"name":acchoilForm.hol_cfg15_name.value,"bez_date":acchoilForm.hol_cfg15_bez_day.value,"end_date":acchoilForm.hol_cfg15_end_date.value,"time_1":holi_15[1],"time_2":holi_15[2],"time_3":holi_15[3],"time_4":holi_15[4]}
                               }                    
                             }
        }
        if(acchoilForm.hol_cfg16_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                              "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                              "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]},
                                              "hol_cfg_id15":{"name":acchoilForm.hol_cfg15_name.value,"bez_date":acchoilForm.hol_cfg15_bez_day.value,"end_date":acchoilForm.hol_cfg15_end_date.value,"time_1":holi_15[1],"time_2":holi_15[2],"time_3":holi_15[3],"time_4":holi_15[4]},
                                              "hol_cfg_id16":{"name":acchoilForm.hol_cfg16_name.value,"bez_date":acchoilForm.hol_cfg16_bez_day.value,"end_date":acchoilForm.hol_cfg16_end_date.value,"time_1":holi_16[1],"time_2":holi_16[2],"time_3":holi_16[3],"time_4":holi_16[4]}
                                            }                    
                        }
        }

        console.log(acc_time_info);
        $.ajax({
            type:"POST",
            url: ip + "/v1/access-time-groups",
            dataType: 'json',
            contentType: 'application/json',
            data : JSON.stringify(acc_time_info),
            success :function(data){
                if(data.error_code == 1){
                    alert("출입 시간 그룹을 추가하였습니다");
                    refresh('access-time-group');
                    console.log(data);

                }
                else if(data.error_code == 2){
                    alert("데이터 베이스 연결 실패.");
                }else if(data.error_code == 3){
                    console.log(data);
                    alert("데이터 삽입 실패");
                }else if(data.error_code == 4){
                    alert("데이터 삭제 실패");
                }else if(data.error_code == 5){
                    alert("데이터 업데이트 실패");
                }else if(data.error_code == 6){
                    alert("데이터 가져오기 실패");
                }else if(data.error_code == 7){
                    alert( "시간(날짜) 형식이 잘못 되었습니다");
                }else if(data.error_code == 8){
                    alert( "데이터가 존재 하지않습니다");
                }else if(data.error_code == 9){
                    alert( "삭제할 수 없는 데이터입니다");
                }else if(data.error_code == 11){
                    alert( "시간이 중복되었습니다");
                }else if(data.error_code == 12){
                    alert( "경로가 잘못 되었습니다");
                }else if(data.error_code == 13){
                    alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
                }else if(data.error_code == 14){
                    alert( "깊이의 최대값(4)을 초과 하였습니다"); 
                }else if(data.error_code == 15){
                    alert( "이미지 저장을 실패하였습니다");
                }else if(data.error_code == 100){
                    alert( "작업 완료");
                }else if(data.error_code == 101){
                    alert( "작업 실패");
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
}
function go_acc_time_group_update2(id){

    var accTimeGroupForm = document.acc_time_group_insert_form;
    var accWeekForm  = document.acc_week_insert_form;
    var acchoilForm = document.acc_holiday_group_form;
    

    if(accTimeGroupForm.acc_time_group_name.value == ""){
        alert("출입 시간 그룹 이름을 입력하세요");
        accTimeGroupForm.acc_time_group_name.focus();
        return;
    }
    //주간 일정 시간범위
    let week_mon = Array();
    let week_tue = Array();
    let week_wed = Array();
    let week_thu = Array();
    let week_fri = Array();
    let week_sat = Array();
    let week_sun = Array();

    for(i=1; i<=4; i++){
    week_mon[i] = (Number($(`#mon_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#mon_time_${i}_end`).val().replace(":",""));
    week_tue[i] = (Number($(`#tue_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#tue_time_${i}_end`).val().replace(":",""));
    week_wed[i] = (Number($(`#wed_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#wed_time_${i}_end`).val().replace(":",""));
    week_thu[i] = (Number($(`#thu_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#thu_time_${i}_end`).val().replace(":",""));
    week_fri[i] = (Number($(`#fri_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#fri_time_${i}_end`).val().replace(":",""));
    week_sat[i] = (Number($(`#sat_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#sat_time_${i}_end`).val().replace(":",""));
    week_sun[i] = (Number($(`#sun_time_${i}_start`).val().replace(":","")) << 16) + Number($(`#sun_time_${i}_end`).val().replace(":",""));
    }
    //휴일 그룹 시간범위
    let holi_1 = Array();
    let holi_2 = Array();
    let holi_3 = Array();
    let holi_4 = Array();
    let holi_5 = Array();
    let holi_6 = Array();
    let holi_7 = Array();
    let holi_8 = Array();
    let holi_9 = Array();
    let holi_10 = Array();
    let holi_11 = Array();
    let holi_12 = Array();
    let holi_13 = Array();
    let holi_14 = Array();
    let holi_15 = Array();
    let holi_16 = Array();
    
    for(i=1; i<=4; i++){
        holi_1[i] = (Number($(`#hol_cfg1_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg1_end${i}`).val().replace(":",""));
        holi_2[i] = (Number($(`#hol_cfg2_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg2_end${i}`).val().replace(":",""));
        holi_3[i] = (Number($(`#hol_cfg3_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg3_end${i}`).val().replace(":",""));
        holi_4[i] = (Number($(`#hol_cfg4_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg4_end${i}`).val().replace(":",""));
        holi_5[i] = (Number($(`#hol_cfg5_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg5_end${i}`).val().replace(":",""));
        holi_6[i] = (Number($(`#hol_cfg6_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg6_end${i}`).val().replace(":",""));
        holi_7[i] = (Number($(`#hol_cfg7_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg7_end${i}`).val().replace(":",""));
        holi_8[i] = (Number($(`#hol_cfg8_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg8_end${i}`).val().replace(":",""));
        holi_9[i] = (Number($(`#hol_cfg9_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg9_end${i}`).val().replace(":",""));
        holi_10[i] = (Number($(`#hol_cfg10_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg10_end${i}`).val().replace(":",""));
        holi_11[i] = (Number($(`#hol_cfg11_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg11_end${i}`).val().replace(":",""));
        holi_12[i] = (Number($(`#hol_cfg12_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg12_end${i}`).val().replace(":",""));
        holi_13[i] = (Number($(`#hol_cfg13_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg13_end${i}`).val().replace(":",""));
        holi_14[i] = (Number($(`#hol_cfg14_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg14_end${i}`).val().replace(":",""));
        holi_15[i] = (Number($(`#hol_cfg15_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg15_end${i}`).val().replace(":",""));
        holi_16[i] = (Number($(`#hol_cfg16_start${i}`).val().replace(":","")) << 16) + Number($(`#hol_cfg16_end${i}`).val().replace(":",""));
        
        }
        if (acchoilForm.hol_cfg1_name.value == "") {
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                
                               }                    
           }
           
        }

        
        if (acchoilForm.hol_cfg1_name.value != "") {
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]}
                               }                    
           }
           
        }
        if(acchoilForm.hol_cfg2_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]}
                               }                    
           }
           
        }
        if(acchoilForm.hol_cfg3_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg4_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg5_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg6_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg7_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]}

                               }                    
           }
        }
        if(acchoilForm.hol_cfg8_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg9_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg10_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg11_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg12_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg13_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_1":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                              "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]}
                                            }                    
                        }
        }
        if(acchoilForm.hol_cfg14_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                 "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                 "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                 "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                 "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]}
                               }                    
           }
        }
        if(acchoilForm.hol_cfg15_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
            "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                        "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                        "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                        "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                        "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                        "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                        "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
            "acc_holiday_group":{
                               
                                 "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                 "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                 "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                 "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                 "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                 "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                 "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                 "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                 "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                 "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                 "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                 "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                 "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                 "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]},
                                 "hol_cfg_id15":{"name":acchoilForm.hol_cfg15_name.value,"bez_date":acchoilForm.hol_cfg15_bez_day.value,"end_date":acchoilForm.hol_cfg15_end_date.value,"time_1":holi_15[1],"time_2":holi_15[2],"time_3":holi_15[3],"time_4":holi_15[4]}
                               }                    
                             }
        }
        if(acchoilForm.hol_cfg16_name.value != ""){
            acc_time_info = {"name": accTimeGroupForm.acc_time_group_name.value,"is_default":0,
                         "acc_week":{"mon_time_1":week_mon[1],"mon_time_2":week_mon[2],"mon_time_3":week_mon[3],"mon_time_4":week_mon[4],
                                     "tue_time_1":week_tue[1],"tue_time_2":week_tue[2],"tue_time_3":week_tue[3],"tue_time_4":week_tue[4],
                                     "wed_time_1":week_wed[1],"wed_time_2":week_wed[2],"wed_time_3":week_wed[3],"wed_time_4":week_wed[4],
                                     "thu_time_1":week_thu[1],"thu_time_2":week_thu[2],"thu_time_3":week_thu[3],"thu_time_4":week_thu[4],
                                     "fri_time_1":week_fri[1],"fri_time_2":week_fri[2],"fri_time_3":week_fri[3],"fri_time_4":week_fri[4],
                                     "sat_time_1":week_sat[1],"sat_time_2":week_sat[2],"sat_time_3":week_sat[3],"sat_time_4":week_sat[4],
                                     "sun_time_1":week_sun[1],"sun_time_2":week_sun[2],"sun_time_3":week_sun[3],"sun_time_4":week_sun[4]},
                         "acc_holiday_group":{
                                            
                                              "hol_cfg_id1":{"name":acchoilForm.hol_cfg1_name.value,"bez_date":acchoilForm.hol_cfg1_bez_day.value,"end_date":acchoilForm.hol_cfg1_end_date.value,"time_1":holi_1[1],"time_2":holi_1[2],"time_3":holi_1[3],"time_4":holi_1[4]},
                                              "hol_cfg_id2":{"name":acchoilForm.hol_cfg2_name.value,"bez_date":acchoilForm.hol_cfg2_bez_day.value,"end_date":acchoilForm.hol_cfg2_end_date.value,"time_1":holi_2[1],"time_2":holi_2[2],"time_3":holi_2[3],"time_4":holi_2[4]},
                                              "hol_cfg_id3":{"name":acchoilForm.hol_cfg3_name.value,"bez_date":acchoilForm.hol_cfg3_bez_day.value,"end_date":acchoilForm.hol_cfg3_end_date.value,"time_1":holi_3[1],"time_2":holi_3[2],"time_3":holi_3[3],"time_4":holi_3[4]},
                                              "hol_cfg_id4":{"name":acchoilForm.hol_cfg4_name.value,"bez_date":acchoilForm.hol_cfg4_bez_day.value,"end_date":acchoilForm.hol_cfg4_end_date.value,"time_1":holi_4[1],"time_2":holi_4[2],"time_3":holi_4[3],"time_4":holi_4[4]},
                                              "hol_cfg_id5":{"name":acchoilForm.hol_cfg5_name.value,"bez_date":acchoilForm.hol_cfg5_bez_day.value,"end_date":acchoilForm.hol_cfg5_end_date.value,"time_1":holi_5[1],"time_2":holi_5[2],"time_3":holi_5[3],"time_4":holi_5[4]},
                                              "hol_cfg_id6":{"name":acchoilForm.hol_cfg6_name.value,"bez_date":acchoilForm.hol_cfg6_bez_day.value,"end_date":acchoilForm.hol_cfg6_end_date.value,"time_1":holi_6[1],"time_2":holi_6[2],"time_3":holi_6[3],"time_4":holi_6[4]},
                                              "hol_cfg_id7":{"name":acchoilForm.hol_cfg7_name.value,"bez_date":acchoilForm.hol_cfg7_bez_day.value,"end_date":acchoilForm.hol_cfg7_end_date.value,"time_1":holi_7[1],"time_2":holi_7[2],"time_3":holi_7[3],"time_4":holi_7[4]},
                                              "hol_cfg_id8":{"name":acchoilForm.hol_cfg8_name.value,"bez_date":acchoilForm.hol_cfg8_bez_day.value,"end_date":acchoilForm.hol_cfg8_end_date.value,"time_1":holi_8[1],"time_2":holi_8[2],"time_3":holi_8[3],"time_4":holi_8[4]},
                                              "hol_cfg_id9":{"name":acchoilForm.hol_cfg9_name.value,"bez_date":acchoilForm.hol_cfg9_bez_day.value,"end_date":acchoilForm.hol_cfg9_end_date.value,"time_1":holi_9[1],"time_2":holi_9[2],"time_3":holi_9[3],"time_4":holi_9[4]},
                                              "hol_cfg_id10":{"name":acchoilForm.hol_cfg10_name.value,"bez_date":acchoilForm.hol_cfg10_bez_day.value,"end_date":acchoilForm.hol_cfg10_end_date.value,"time_1":holi_10[1],"time_2":holi_10[2],"time_3":holi_10[3],"time_4":holi_10[4]},
                                              "hol_cfg_id11":{"name":acchoilForm.hol_cfg11_name.value,"bez_date":acchoilForm.hol_cfg11_bez_day.value,"end_date":acchoilForm.hol_cfg11_end_date.value,"time_1":holi_11[1],"time_2":holi_11[2],"time_3":holi_11[3],"time_4":holi_11[4]},
                                              "hol_cfg_id12":{"name":acchoilForm.hol_cfg12_name.value,"bez_date":acchoilForm.hol_cfg12_bez_day.value,"end_date":acchoilForm.hol_cfg12_end_date.value,"time_1":holi_12[1],"time_2":holi_12[2],"time_3":holi_12[3],"time_4":holi_12[4]},
                                              "hol_cfg_id13":{"name":acchoilForm.hol_cfg13_name.value,"bez_date":acchoilForm.hol_cfg13_bez_day.value,"end_date":acchoilForm.hol_cfg13_end_date.value,"time_1":holi_13[1],"time_2":holi_13[2],"time_3":holi_13[3],"time_4":holi_13[4]},
                                              "hol_cfg_id14":{"name":acchoilForm.hol_cfg14_name.value,"bez_date":acchoilForm.hol_cfg14_bez_day.value,"end_date":acchoilForm.hol_cfg14_end_date.value,"time_1":holi_14[1],"time_2":holi_14[2],"time_3":holi_14[3],"time_4":holi_14[4]},
                                              "hol_cfg_id15":{"name":acchoilForm.hol_cfg15_name.value,"bez_date":acchoilForm.hol_cfg15_bez_day.value,"end_date":acchoilForm.hol_cfg15_end_date.value,"time_1":holi_15[1],"time_2":holi_15[2],"time_3":holi_15[3],"time_4":holi_15[4]},
                                              "hol_cfg_id16":{"name":acchoilForm.hol_cfg16_name.value,"bez_date":acchoilForm.hol_cfg16_bez_day.value,"end_date":acchoilForm.hol_cfg16_end_date.value,"time_1":holi_16[1],"time_2":holi_16[2],"time_3":holi_16[3],"time_4":holi_16[4]}
                                            }                    
                        }
        }

        console.log(acc_time_info);
        $.ajax({
            type:"PUT",
            url: ip + "/v1/access-time-groups/" + id,
            dataType: 'json',
            contentType: 'application/json',
            data : JSON.stringify(acc_time_info),
            success :function(data){
                if(data.error_code == 1){
                    alert("출입 시간 그룹을 수정하였습니다");
                    refresh('access-time-group');
                    console.log(data);

                }
                else if(data.error_code == 2){
                    alert("데이터 베이스 연결 실패.");
                }else if(data.error_code == 3){
                    console.log(data);
                    alert("데이터 삽입 실패");
                    console.log(data);
                }else if(data.error_code == 4){
                    alert("데이터 삭제 실패");
                }else if(data.error_code == 5){
                    console.log(data);
                    alert("데이터 업데이트 실패");
                }else if(data.error_code == 6){
                    console.log(data);
                    alert("데이터 가져오기 실패");
                }else if(data.error_code == 7){
                    console.log(data);
                    alert( "시간(날짜) 형식이 잘못 되었습니다");
                }else if(data.error_code == 8){
                    console.log(data);
                    alert( "데이터가 존재 하지않습니다");
                }else if(data.error_code == 9){
                    console.log(data);
                    alert( "삭제할 수 없는 데이터입니다");
                }else if(data.error_code == 11){
                    console.log(data);
                    alert( "시간이 중복되었습니다");
                }else if(data.error_code == 12){
                    console.log(data);
                    alert( "경로가 잘못 되었습니다");
                }else if(data.error_code == 13){
                    console.log(data);
                    alert( "페이지 번호 또는, 총 페이지 수가 잘못되었습니다");
                }else if(data.error_code == 14){
                    console.log(data);
                    alert( "깊이의 최대값(4)을 초과 하였습니다"); 
                }else if(data.error_code == 15){
                    console.log(data);
                    alert( "이미지 저장을 실패하였습니다");
                }else if(data.error_code == 100){
                    console.log(data);
                    alert( "작업 완료");
                }else if(data.error_code == 101){
                    console.log(data);
                    alert( "작업 실패");
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });

}


function acc_time_group_insert_info() {
    $.ajax({
        type: "GET",
        url: ip + "/v1/access-weeks",
        dataType: 'json',
        contentType: 'application/json',
        async: false,
        success: function (data) {
            console.log(data);
            let totalRows = data.access_week_infos.length;

            for (let i = 0; i < totalRows; i++) {
                var str = '<option value="'+ data.access_week_infos[i].id+'">'+data.access_week_infos[i].name+'</option>';
                $("#acc_week_id").append(str);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
    $.ajax({
        type: "GET",
        url: ip + "/v1/access-holiday-groups",
        dataType: 'json',
        contentType: 'application/json',
        async: false,
        success: function (data) {
            console.log(data);
            let totalRows = data.access_holiday_group_infos.length;

            for (let i = 0; i < totalRows; i++) {
                var str = '<option value="'+ data.access_holiday_group_infos[i].id+'">'+data.access_holiday_group_infos[i].name+'</option>';
                $("#acc_holiday_group_id").append(str);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

// function go_acc_time_group_update(id) {
  
//     var f = document.acc_time_group_insert_form;
//     if (f.access_time_group_name.value == "") {
//         alert("출입 시간 그룹 이름을 입력해주세요."); f.access_time_group_name.focus();
//         return;
//     }
//     var is_default = f.is_default.value;
//     //var is_default = parseInt(is_default);
//     if(is_default == "1"){
//         is_default = true;
//     }
//     else if(is_default == "0"){
//         is_default = false;
//     }
//     var acc_week_id = f.acc_week_id.value;
//     var acc_holiday_group_id = f.acc_holiday_group_id.value;
//     acc_week_id = parseInt(acc_week_id);
//     acc_holiday_group_id = parseInt(acc_holiday_group_id);
  
//     var access_time_group_info = {"name": f.access_time_group_name.value, "is_default": is_default, "acc_week_id": acc_week_id, "acc_holiday_group_id": acc_holiday_group_id};
//     console.log(access_time_group_info);
//     $.ajax({
//         type : "PUT",
//         url : ip + "/v1/access-time-groups/" + id,
//         dataType : 'json',
//         contentType : 'application/json',
//         data : JSON.stringify(access_time_group_info),
//         success : function(data) {
//             console.log(data);
//             if (data.error_code == 1) {
//                 alert("출입 시간 그룹을 수정하였습니다.");
//                 refresh('access-time-group');
//             }
//             else{
//                 alert("출입 시간 그룹이 중복되었습니다.");
//             }
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
            
//             console.log(jqXHR);
//             console.log("error");
//         }
//     });
// }

function acc_time_group_update_info() {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-time-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            $("#access_time_group_name").val(data.access_time_group_info.name);
            if (data.access_time_group_info.is_default) {
                $("#is_default option[value='1']").attr("selected", "selected");
            } else {
                $("#is_default option[value='0']").attr("selected", "selected");
            }
            acc_week_id = data.access_time_group_info.acc_week_id;
            acc_holiday_group_id = data.access_time_group_info.acc_holiday_group_id;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });

    $.ajax({
        type: "GET",
        url: ip + "/v1/access-weeks",
        dataType: 'json',
        contentType: 'application/json',
        async: false,
        success: function (data) {
            //console.log(data);
            let totalRows = data.access_week_infos.length;
            for (let i = 0; i < totalRows; i++) {
                var str = '<option value="'+ data.access_week_infos[i].id+'">'+data.access_week_infos[i].name+'</option>';
                $("#acc_week_id").append(str);
            }
            $("#acc_week_id option[value='"+acc_week_id+"']").attr("selected", "selected");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });

    $.ajax({
        type: "GET",
        url: ip + "/v1/access-holiday-groups",
        dataType: 'json',
        contentType: 'application/json',
        async: false,
        success: function (data) {
            //console.log(data);
            let totalRows = data.access_holiday_group_infos.length;
            for (let i = 0; i < totalRows; i++) {
                var str = '<option value="'+ data.access_holiday_group_infos[i].id+'">'+data.access_holiday_group_infos[i].name+'</option>';
                $("#acc_holiday_group_id").append(str);
            }
            $("#acc_holiday_group_id option[value='"+acc_holiday_group_id+"']").attr("selected", "selected");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}


function acc_holiday_group_delete() {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/access-holiday-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 휴일 그룹을 삭제하였습니다.");
                    parent.refresh('access-holiday-group');
                    window.close();
                }
                else{
                    alert("삭제하지 못했습니다.");
                    parent.refresh('access-holiday-group');
                    window.close();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/access-holiday-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 휴일 그룹을 모두 삭제하였습니다.");
            parent.refresh('access-holiday-group');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 휴일 그룹을 일부만 삭제하였습니다.");
            parent.refresh('access-holiday-group');
            window.close();
        }
        else {
            alert("선택된 휴일 그룹을 삭제하지 못했습니다.");
            parent.refresh('access-holiday-group');
            window.close();
        }
    }
}

function go_acc_holiday_group_insert() {
    var f = document.acc_holiday_group_form;

    if (f.access_holiday_group_name.value == "") {
        alert("휴일 그룹 이름을 입력해주세요."); f.access_holiday_group_name.focus();
        return;
    }

    var access_holiday_group_info = {"name":f.access_holiday_group_name.value,"hol_cfg_id1":f.hol_cfg_id1.value,"hol_cfg_id2":f.hol_cfg_id2.value,"hol_cfg_id3":f.hol_cfg_id3.value,"hol_cfg_id4":f.hol_cfg_id4.value,"hol_cfg_id5":f.hol_cfg_id5.value,"hol_cfg_id6":f.hol_cfg_id6.value,"hol_cfg_id7":f.hol_cfg_id7.value,"hol_cfg_id8":f.hol_cfg_id8.value,"hol_cfg_id9":f.hol_cfg_id9.value,"hol_cfg_id10":f.hol_cfg_id10.value,"hol_cfg_id11":f.hol_cfg_id11.value,"hol_cfg_id12":f.hol_cfg_id12.value,"hol_cfg_id13":f.hol_cfg_id13.value,"hol_cfg_id14":f.hol_cfg_id14.value,"hol_cfg_id15":f.hol_cfg_id15.value,"hol_cfg_id16":f.hol_cfg_id16.value};

    $.ajax({
        type : "POST",
        url : ip + "/v1/access-holiday-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(access_holiday_group_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("휴일 그룹을 추가하였습니다.");
                refresh('access-holiday-group');
                window.close();
            }
            else{
                alert("휴일 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function go_acc_holiday_group_update(id) {
    var f = document.acc_holiday_group_form;

    if (f.access_holiday_group_name.value == "") {
        alert("출입 시간 그룹 이름을 입력해주세요."); f.access_holiday_group_name.focus();
        return;
    }

    var access_holiday_group_info = {"name":f.access_holiday_group_name.value,"hol_cfg_id1":f.hol_cfg_id1.value,"hol_cfg_id2":f.hol_cfg_id2.value,"hol_cfg_id3":f.hol_cfg_id3.value,"hol_cfg_id4":f.hol_cfg_id4.value,"hol_cfg_id5":f.hol_cfg_id5.value,"hol_cfg_id6":f.hol_cfg_id6.value,"hol_cfg_id7":f.hol_cfg_id7.value,"hol_cfg_id8":f.hol_cfg_id8.value,"hol_cfg_id9":f.hol_cfg_id9.value,"hol_cfg_id10":f.hol_cfg_id10.value,"hol_cfg_id11":f.hol_cfg_id11.value,"hol_cfg_id12":f.hol_cfg_id12.value,"hol_cfg_id13":f.hol_cfg_id13.value,"hol_cfg_id14":f.hol_cfg_id14.value,"hol_cfg_id15":f.hol_cfg_id15.value,"hol_cfg_id16":f.hol_cfg_id16.value};

    $.ajax({
        type : "PUT",
        url : ip + "/v1/access-holiday-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(access_holiday_group_info),
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                alert("휴일 그룹을 수정하였습니다.");
                refresh('access-holiday-group');
                window.close();
            }
            else{
                alert("수정하지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function acc_holiday_group_update() {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-holiday-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            $("#access_holiday_group_name").val(data.access_holiday_group_info.name);
            $("#hol_cfg_id1").val(data.access_holiday_group_info.hol_cfg_id1);
            $("#hol_cfg_id2").val(data.access_holiday_group_info.hol_cfg_id2);
            $("#hol_cfg_id3").val(data.access_holiday_group_info.hol_cfg_id3);
            $("#hol_cfg_id4").val(data.access_holiday_group_info.hol_cfg_id4);
            $("#hol_cfg_id5").val(data.access_holiday_group_info.hol_cfg_id5);
            $("#hol_cfg_id6").val(data.access_holiday_group_info.hol_cfg_id6);
            $("#hol_cfg_id7").val(data.access_holiday_group_info.hol_cfg_id7);
            $("#hol_cfg_id8").val(data.access_holiday_group_info.hol_cfg_id8);
            $("#hol_cfg_id9").val(data.access_holiday_group_info.hol_cfg_id9);
            $("#hol_cfg_id10").val(data.access_holiday_group_info.hol_cfg_id10);
            $("#hol_cfg_id11").val(data.access_holiday_group_info.hol_cfg_id11);
            $("#hol_cfg_id12").val(data.access_holiday_group_info.hol_cfg_id12);
            $("#hol_cfg_id13").val(data.access_holiday_group_info.hol_cfg_id13);
            $("#hol_cfg_id14").val(data.access_holiday_group_info.hol_cfg_id14);
            $("#hol_cfg_id15").val(data.access_holiday_group_info.hol_cfg_id15);
            $("#hol_cfg_id16").val(data.access_holiday_group_info.hol_cfg_id16);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function acc_week_update() {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-weeks/" + id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            //console.log(data);
            $("#access_week_name").val(data.access_week_info.name);
            if (data.access_week_info.mon_time_1 != 0) {
                let mon_time_1 = data.access_week_info.mon_time_1;
                $("#mon_time_1_start").val(mon_time_1 >> 16);
                $("#mon_time_1_end").val(parseInt(String(mon_time_1.toString(2)).slice(-16),2));
                $("#mon_1").show();
            }
            if(data.access_week_info.mon_time_2 != 0) {
                let mon_time_2 = data.access_week_info.mon_time_2;
                $("#mon_time_2_start").val(mon_time_2 >> 16);
                $("#mon_time_2_end").val(parseInt(String(mon_time_2.toString(2)).slice(-16),2));
                $("#mon_2").show();
            }
            if(data.access_week_info.mon_time_3 != 0) {
                let mon_time_3 = data.access_week_info.mon_time_3;
                $("#mon_time_3_start").val(mon_time_3 >> 16);
                $("#mon_time_3_end").val(parseInt(String(mon_time_3.toString(2)).slice(-16),2));
                $("#mon_3").show();
            }
            if(data.access_week_info.mon_time_4 != 0) {
                let mon_time_4 = data.access_week_info.mon_time_4;
                $("#mon_time_4_start").val(mon_time_4 >> 16);
                $("#mon_time_4_end").val(parseInt(String(mon_time_4.toString(2)).slice(-16),2));
                $("#mon_4").show();
            }
            if(data.access_week_info.tue_time_1 != 0) {
                let tue_time_1 = data.access_week_info.tue_time_1;
                $("#tue_time_1_start").val(tue_time_1 >> 16);
                $("#tue_time_1_end").val(parseInt(String(tue_time_1.toString(2)).slice(-16),2));
                $("#tue_1").show();
            }
            if(data.access_week_info.tue_time_2 != 0) {
                let tue_time_2 = data.access_week_info.tue_time_2;
                $("#tue_time_2_start").val(tue_time_2 >> 16);
                $("#tue_time_2_end").val(parseInt(String(tue_time_2.toString(2)).slice(-16),2));
                $("#tue_2").show();
            }
            if(data.access_week_info.tue_time_3 != 0) {
                let tue_time_3 = data.access_week_info.tue_time_3;
                $("#tue_time_3_start").val(tue_time_3 >> 16);
                $("#tue_time_3_end").val(parseInt(String(tue_time_3.toString(2)).slice(-16),2));
                $("#tue_3").show();
            }
            if(data.access_week_info.tue_time_4 != 0) {
                let tue_time_4 = data.access_week_info.tue_time_4;
                $("#tue_time_4_start").val(tue_time_4 >> 16);
                $("#tue_time_4_end").val(parseInt(String(tue_time_4.toString(2)).slice(-16),2));
                $("#tue_4").show();
            }
            if(data.access_week_info.wed_time_1 != 0) {
                let wed_time_1 = data.access_week_info.wed_time_1;
                $("#wed_time_1_start").val(wed_time_1 >> 16);
                $("#wed_time_1_end").val(parseInt(String(wed_time_1.toString(2)).slice(-16),2));
                $("#wed_1").show();
            }
            if(data.access_week_info.wed_time_2 != 0) {
                let wed_time_2 = data.access_week_info.wed_time_2;
                $("#wed_time_2_start").val(wed_time_2 >> 16);
                $("#wed_time_2_end").val(parseInt(String(wed_time_2.toString(2)).slice(-16),2));
                $("#wed_2").show();
            }
            if(data.access_week_info.wed_time_3 != 0) {
                let wed_time_3 = data.access_week_info.wed_time_3;
                $("#wed_time_3_start").val(wed_time_3 >> 16);
                $("#wed_time_3_end").val(parseInt(String(wed_time_3.toString(2)).slice(-16),2));
                $("#wed_3").show();
            }
            if(data.access_week_info.wed_time_4 != 0) {
                let wed_time_4 = data.access_week_info.wed_time_4;
                $("#wed_time_4_start").val(wed_time_4 >> 16);
                $("#wed_time_4_end").val(parseInt(String(wed_time_4.toString(2)).slice(-16),2));
                $("#wed_4").show();
            }
            if(data.access_week_info.thu_time_1 != 0) {
                let thu_time_1 = data.access_week_info.thu_time_1;
                $("#thu_time_1_start").val(thu_time_1 >> 16);
                $("#thu_time_1_end").val(parseInt(String(thu_time_1.toString(2)).slice(-16),2));
                $("#thu_1").show();
            }
            if(data.access_week_info.thu_time_2 != 0) {
                let thu_time_2 = data.access_week_info.thu_time_2;
                $("#thu_time_2_start").val(thu_time_2 >> 16);
                $("#thu_time_2_end").val(parseInt(String(thu_time_2.toString(2)).slice(-16),2));
                $("#thu_2").show();
            }
            if(data.access_week_info.thu_time_3 != 0) {
                let thu_time_3 = data.access_week_info.thu_time_3;
                $("#thu_time_3_start").val(thu_time_3 >> 16);
                $("#thu_time_3_end").val(parseInt(String(thu_time_3.toString(2)).slice(-16),2));
                $("#thu_3").show();
            }
            if(data.access_week_info.thu_time_4 != 0) {
                let thu_time_4 = data.access_week_info.thu_time_4;
                $("#thu_time_4_start").val(thu_time_4 >> 16);
                $("#thu_time_4_end").val(parseInt(String(thu_time_4.toString(2)).slice(-16),2));
                $("#thu_4").show();
            }
            if(data.access_week_info.fri_time_1 != 0) {
                let fri_time_1 = data.access_week_info.fri_time_1;
                $("#fri_time_1_start").val(fri_time_1 >> 16);
                $("#fri_time_1_end").val(parseInt(String(fri_time_1.toString(2)).slice(-16),2));
                $("#fri_1").show();
            }
            if(data.access_week_info.fri_time_2 != 0) {
                let fri_time_2 = data.access_week_info.fri_time_2;
                $("#fri_time_2_start").val(fri_time_2 >> 16);
                $("#fri_time_2_end").val(parseInt(String(fri_time_2.toString(2)).slice(-16),2));
                $("#fri_2").show();
            }
            if(data.access_week_info.fri_time_3 != 0) {
                let fri_time_3 = data.access_week_info.fri_time_3;
                $("#fri_time_3_start").val(fri_time_3 >> 16);
                $("#fri_time_3_end").val(parseInt(String(fri_time_3.toString(2)).slice(-16),2));
                $("#fri_3").show();
            }
            if(data.access_week_info.fri_time_4 != 0) {
                let fri_time_4 = data.access_week_info.fri_time_4;
                $("#fri_time_4_start").val(fri_time_4 >> 16);
                $("#fri_time_4_end").val(parseInt(String(fri_time_4.toString(2)).slice(-16),2));
                $("#fri_4").show();
            }
            if(data.access_week_info.sat_time_1 != 0) {
                let sat_time_1 = data.access_week_info.sat_time_1;
                $("#sat_time_1_start").val(sat_time_1 >> 16);
                $("#sat_time_1_end").val(parseInt(String(sat_time_1.toString(2)).slice(-16),2));
                $("#sat_1").show();
            }
            if(data.access_week_info.sat_time_2 != 0) {
                let sat_time_2 = data.access_week_info.sat_time_2;
                $("#sat_time_2_start").val(sat_time_2 >> 16);
                $("#sat_time_2_end").val(parseInt(String(sat_time_2.toString(2)).slice(-16),2));
                $("#sat_2").show();
            }
            if(data.access_week_info.sat_time_3 != 0) {
                let sat_time_3 = data.access_week_info.sat_time_3;
                $("#sat_time_3_start").val(sat_time_3 >> 16);
                $("#sat_time_3_end").val(parseInt(String(sat_time_3.toString(2)).slice(-16),2));
                $("#sat_3").show();
            }
            if(data.access_week_info.sat_time_4 != 0) {
                let sat_time_4 = data.access_week_info.sat_time_4;
                $("#sat_time_4_start").val(sat_time_4 >> 16);
                $("#sat_time_4_end").val(parseInt(String(sat_time_4.toString(2)).slice(-16),2));
                $("#sat_4").show();
            }
            if(data.access_week_info.sun_time_1 != 0) {
                let sun_time_1 = data.access_week_info.sun_time_1;
                $("#sun_time_1_start").val(sun_time_1 >> 16);
                $("#sun_time_1_end").val(parseInt(String(sun_time_1.toString(2)).slice(-16),2));
                $("#sun_1").show();
            }
            if(data.access_week_info.sun_time_2 != 0) {
                let sun_time_2 = data.access_week_info.sun_time_2;
                $("#sun_time_2_start").val(sun_time_2 >> 16);
                $("#sun_time_2_end").val(parseInt(String(sun_time_2.toString(2)).slice(-16),2));
                $("#sun_2").show();
            }
            if(data.access_week_info.sun_time_3 != 0) {
                let sun_time_3 = data.access_week_info.sun_time_3;
                $("#sun_time_3_start").val(sun_time_3 >> 16);
                $("#sun_time_3_end").val(parseInt(String(sun_time_3.toString(2)).slice(-16),2));
                $("#sun_3").show();
            }
            if(data.access_week_info.sun_time_4 != 0) {
                let sun_time_4 = data.access_week_info.sun_time_4;
                $("#sun_time_4_start").val(sun_time_4 >> 16);
                $("#sun_time_4_end").val(parseInt(String(sun_time_4.toString(2)).slice(-16),2));
                $("#sun_4").show();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function device_scan(rs,job,search_time) {
    
    if (job == "scan") {
        $("#device-scan-seraching").show();
        $("#device-scan-content").hide();
        $("body").append('<iframe id="subscribe" src="../phpMQTT/subscribe.php" width="500" height="0"></iframe>');
        $.ajax({
            type : "GET",
            url : ip + "/v1/device-scan?type=0&timeout_sec=3000&reply_to=devices/devices&reply_method=MQTT&reply_msg=device-scan",
            dataType : 'json',
            contentType : 'application/json'
        });
    } else {
        if (rs != undefined && rs != "") {
            rs = String(rs).replace('<?="','');
            rs = String(rs).replace('"?>','');
            let data = JSON.parse(rs);
            //console.log(data.reply_data.device_scan_infos);
            if (data.reply_data.device_count > 0) {
                let str = "";
                let searchRows = 0;
                let totalRows = data.reply_data.device_count;
                $(parent.document).find("#device-scan-content").empty();
                
                for (let i = 0; i < totalRows; i++) {
                    let device_scan_ip          = data.reply_data.device_scan_infos[i].ip;
                    let device_scan_tcp_port   = data.reply_data.device_scan_infos[i].tcp_port;
                    let device_scan_web_port    = data.reply_data.device_scan_infos[i].web_port;
                    let device_scan_mac_addr    = data.reply_data.device_scan_infos[i].mac_addr;
                    let device_scan_subnet      = data.reply_data.device_scan_infos[i].subnet;
                    let device_scan_gateway     = data.reply_data.device_scan_infos[i].gateway;
                    let device_scan_serial_no   = data.reply_data.device_scan_infos[i].serial_no;
                    let device_scan_is_active   = data.reply_data.device_scan_infos[i].is_active;
                    let device_scan_enable_dhcp = data.reply_data.device_scan_infos[i].enable_dhcp;
                    let device_scan_model_name  = data.reply_data.device_scan_infos[i].model_name;
                    let strDhcp                 = (device_scan_enable_dhcp == 1) ? "ON" : "OFF";
                    let strIsActive             = (device_scan_is_active == 1) ? "활성화" : "비활성화";
                    str += '<tr class="deviceScanForm" onClick=\'javascript: go_device_scan_insert_info("'+device_scan_ip+'","'+device_scan_tcp_port+'","'+device_scan_web_port+'","'+device_scan_model_name+'","'+device_scan_serial_no+'");\' style="cursor: pointer;">';
                    str += '<td class="py-2">'+( searchRows + 1 )+'</td>';
                    str += '<td class="py-2">'+device_scan_model_name+'</td>';
                    str += '<td class="py-2">'+device_scan_ip+'</td>';
                    str += '<td class="py-2">'+device_scan_tcp_port+'</td>';
                    str += '<td class="py-2">'+device_scan_web_port+'</td>';
                    str += '<td class="py-2">'+device_scan_mac_addr.toUpperCase()+'</td>';
                    str += '<td class="py-2">'+device_scan_subnet+'</td>';
                    str += '<td class="py-2">'+device_scan_gateway+'</td>';
                    str += '<td class="py-2">'+device_scan_serial_no.toUpperCase()+'</td>';
                    // str += '<td class="py-2">'+strIsActive+'</td>';
                    // str += '<td class="py-2">'+strDhcp+'</td>';
                    // str += '<td class="py-2">';
                    // str += '<div class="position-relative">';
                    // str += '<a class="link-dark d-inline-block" href="#">';
                    // str += '네트워크 변경';
                    // str += '</a>';
                    str += '</div>';
                    str += '</td>';
                    str += '</tr>';
                    searchRows++;
                }
                totalRows = searchRows;
                $(parent.document).find("#device-scan-content").append(str);
                $(parent.document).find("#device-scan-seraching").hide();
                $(parent.document).find("#device-scan-content").show();
                $(parent.document).find("#device-scan-total-row").html("총 "+ totalRows +"개");
                $(parent.document).find("#subscribe").remove();
            }
            else{
                let str = "";
                $(parent.document).find("#device-scan-content").empty();
                str += '<tr><td style="border: 0;"></td></tr>';
                str += '<tr><td style="border: 0;"></td></tr>';
                str += '<tr><td style="border: 0;"></td></tr>';
                str += '<tr class="">';
                str += '<td class="py-2" style="border: 0;">';
                str += '<div>';
                str += '<span >Device를 찾지 못했습니다.</span>';
                str += '</div>';
                str += '</td>';
                str += '</tr>';
                $(parent.document).find("#device-scan-content").append(str);
                $(parent.document).find("#device-scan-total-row").html("총 0개");
            }
        }
        else{
            let str = "";
            $(parent.document).find("#device-scan-content").empty();
            str += '<tr><td style="border: 0;"></td></tr>';
            str += '<tr><td style="border: 0;"></td></tr>';
            str += '<tr><td style="border: 0;"></td></tr>';
            str += '<tr class="">';
            str += '<td class="py-2" style="border: 0;">';
            str += '<div>';
            str += '<span >Device를 찾지 못했습니다.</span>';
            str += '</div>';
            str += '</td>';
            str += '</tr>';
            $(parent.document).find("#device-scan-content").append(str);
            $(parent.document).find("#device-scan-total-row").html("총 0개");
        }
    }
}


function user_scan(rs) {
        console.log("user_scan");
        
        rs = String(rs).replace('<?="','');
        rs = String(rs).replace('"?>','');
        //let data = JSON.parse(rs);
        
        var job = 'scan';
        
        //var progress_cur = data.reply_info.progress_cur+'%';
        
        //alert(progress_cur);
        //console.log(document.getElementById('#progress_cur'));
        //document.getElementById('#progress_cur').html(progress_cur);
        //$(parent.document).find("#progress_cur").append(progress_cur);
        go_user_insert2(job,rs);
}
// function user_scan2(rs) {
//     console.log("user_scan");
    
//     rs = String(rs).replace('<?="','');
//     rs = String(rs).replace('"?>','');
//     //let data = JSON.parse(rs);
    
//     var job = 'scan';
    
//     //var progress_cur = data.reply_info.progress_cur+'%';
    
//     //alert(progress_cur);
//     //console.log(document.getElementById('#progress_cur'));
//     //document.getElementById('#progress_cur').html(progress_cur);
//     //$(parent.document).find("#progress_cur").append(progress_cur);
//     go_user_update2(job,rs,0);
// }


function changeCount(strUrl) {
    location.href = strUrl +".php?page_count=" + $("#page_count_change").val() + "&search_str="+searchStr+"&search_start_num="+search_start_num;
}
function changeCount2(strUrl) {
    
    var page_no =$("#page_count_change").val().slice(0, -1);
    location.href = strUrl +".php?page_no=" + page_no + "&search_str="+searchStr+"&search_start_num="+search_start_num;
}


function goPage(pageNum, strUrl) {
    if (pageNum == "search_page") {
        pageNum = $("#search_page").val();
    }
    if (pageNum < 1 || totalPage < pageNum || pageNum == page_no || totalPage == undefined) { return false; }
        
    location.href=strUrl+".php?page_no="+pageNum+"&search_str="+searchStr+"&search_start_num="+search_start_num+"";
}

function goSearch(strUrl) {
    var f = document.search_form;
    if (strUrl !== "device-scan") {
        f.page_count.value = $("#page_count_change").val();
    }
    f.action = strUrl+".php";
    f.submit();
}

function goInsert(strUrl) {
    window.open(strUrl+"-insert.php", strUrl+"-insert", "height=700px, width=1000px");
}

function goUpdate(id, strUrl) {
    window.open("", strUrl+"-update", "height=700px, width=1000px");
    var f = document.contents_show_form;
    f.id.value = id;
    f.method = "POST";
    f.target = strUrl+"-update";
    f.action = strUrl+"-update.php";
    f.submit();
}

function goDelete(id, strUrl) {
    if (confirm("삭제하시겠습니까?")) {
        var f = document.contents_show_form;
        if (id == "more") {
            f.method = "POST";
            f.target = "hiddenfrm";
            f.action = strUrl+"-delete.php";
            f.submit();
        }
        else{
            f.id.value = id;
            f.job.value = "1";
            f.method = "POST";
            f.target = "hiddenfrm";
            f.action = strUrl+"-delete.php";
            f.submit();
        }
    }
}

function refresh(strUrl) {
    location.href = strUrl+".php";
}

function displayOnOff() {
    if ( $('#sub-sidebar').css('display') === 'none' ) {
        $('#sub-sidebar').show();
        $("#type-content").addClass("display-content");
        $(".sub-sidebar-btn").text("◀");
    }
    else {
        $('#sub-sidebar').hide();
        $("#type-content").removeClass("display-content");
        $(".sub-sidebar-btn").text("▶");
        
    }
}

function addTab(link) {
    // If tab already exist in the list, return
    var rel = $(link).attr("rel");
    var child = $(link).attr("child");
    if (rel == "dashboard") {
        $("iframe").hide();
        $("#main_index").show();
        $("#tabs li").removeClass("current");
        return;
    }
    if ($("#" + rel).length != 0) {
        sideMenuClick(rel);
    }
    else{
        // hide other tabs
        $("#tabs li").removeClass("current");
        $("iframe").hide();

        // add new tab and related content-wrap
        $("#tabList").append('<div class="list-group-item list-group-item-action" id="' + rel + '" child="'+child+'" onclick="javascript:tab(this);"><p class="mb-0">'+$(link).text()+'</p><a class="list-group-item-closer text-muted" onclick="javascript:remove(this);" href="#"><i class="gd-close"></i></a></div>');
        $("#tabs>#home").after("<li class='current'><a class='tab' id='" + rel + "' child='"+child+"' onclick='javascript:tab(this);' href='#'><span class='side-nav-fadeout-on-closed media-body'>"+$(link).text()+"</span></a><a href='#' onclick='javascript:remove(this);' class='remove'>x</a></li>");

        $("#main_content").append('<iframe class="tab_content" id="'+rel+'_content" src="'+$(link).attr("title")+'" style="width: 100%; border: 0; position: relative; top: -3rem;"></iframe>');
        
        $("#" + $(link).attr("rel") + "_content").show();

        if($(link).text() == "실시간 이벤트"){
           
            $(".remove").attr("onclick","devices_events('off');javascript:remove(this);");
        }
        
    }        
}
function sideMenuClick(contentname) {
    $("iframe").hide();
    $("#tabs li").removeClass("current");

    // show current tab
    $("#" + contentname+"_content").show();
    $("#" + contentname).parent().addClass("current");
}
var cnt = 0;
function tab(obj) {
    // Get the tab name
    if (cnt > 0 ) {cnt = 0; return;}
    var contentname = $(obj).attr("id") + "_content";

    // hide all other tabs
    $("iframe").hide();
    $("#tabs li").removeClass("current");

    // show current tab
    $("#" + contentname).show();
    $(obj).parent().addClass("current");

    $('#' + $(obj).attr('child') + '_parent').trigger('click');
    $('a[rel="'+$(obj).attr("id")+'"]').trigger('click');
}

function remove(obj) {
    // Get the tab name
    var count = 0;
    var tabid = $(obj).parent().find(".tab").attr("id");
    if(tabid == undefined){
        tabid = $(obj).parent().attr("id");
        count = 1;
        cnt++;
    }

    // remove tab and related content-wrap
    var contentname = tabid + "_content";
    $("#" + contentname).remove();
    $(obj).parent().remove();
    if (count == 0) {
        $("#tabList #"+tabid).remove();
    }else{
        $("#tabs #"+tabid).parent().remove();
    }

    // if there is no current tab and if there are still tabs left, show the first
    if ($("#tabs li.current").length == 0 && $("#tabs li").length > 1) {
        // find the first tab
        var firsttab = $("#tabs li:nth-child(2)");
        firsttab.addClass("current");

        // get its link name and show related content-wrap
        var firsttabid = $(firsttab).find("a.tab").attr("id");
        var firsttabchild = $(firsttab).find("a.tab").attr("child");
        $("#" +firsttabchild+"_parent").trigger("click");
        $("a[rel='"+firsttabid+"']").trigger("click");
    }
    else if ($("#tabs li").length == 1) {
        goDashboard();
    }
}

function clearAllTab() {
    $("#tabList>div").remove();
    $("#tabs>li").not("#home").remove();
    $("#main_content>iframe").not("#main_index").remove();
    goDashboard();
}

function goDashboard() {
    $("#sideNav a.dashboardTab").trigger("click");
}




function device_group_del() {
    let checkedFullArr = $(".checkbox_true_full");
    let deviceGroupIdArr = new Array();

    for (let i = 0; i < checkedFullArr.length; i++) {
        if (checkedFullArr[i].id.substring(7,8) != "_") {
            deviceGroupIdArr.push(checkedFullArr[i].id.substring(0,8) + "_span");
        }else{
            deviceGroupIdArr.push(checkedFullArr[i].id.substring(0,7) + "_span");
        }
    }
    let checkedPartArr = $(".checkbox_true_part");
    for (let i = 0; i < checkedPartArr.length; i++) {
        if (checkedPartArr[i].id.substring(7,8) != "_") {
            deviceGroupIdArr.push(checkedPartArr[i].id.substring(0,8) + "_span");
        }else{
            deviceGroupIdArr.push(checkedPartArr[i].id.substring(0,7) + "_span");
        }
    }
       if (deviceGroupIdArr.length == 0) {
        alert("삭제할 그룹이 없습니다.");
    } else {
        if (confirm("삭제하시겠습니까?")) {
            device_group_delete("devices", deviceGroupIdArr);
        }
    }
}

function deviceGroupSet(job, id) {
    var f = document.deviceGroupForm;
    if (job == "add" || job == "addBtn") {
        $("#deviceGroupModal h4").text('단말기 그룹 추가');
        $("#deviceGroupModal h4").append('<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>');
        f.parentId.value = id;
        $("input[name=groupName]").val("");
        $("#deviceGroupBtn").remove();
        if (job == "addBtn") {
            var strBtn = '<button type="button" id="deviceGroupBtn" onclick="javascript: go_device_group_insert(\'simpleDeviceGroup\');" class="btn_success">저 장</button>';
        } else {
            var strBtn = '<button type="button" id="deviceGroupBtn" onclick="javascript: go_device_group_insert(\'devices\');" class="btn_success">저 장</button>';
        }
        $(".btn_append").append(strBtn);
        //$("form[name=deviceGroupForm]").append(strBtn);
    } else if(job == "upd") {
        $("#deviceGroupModal h4").text("단말기 그룹 수정");
        $("#deviceGroupModal h4").append('<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>');
        device_group_update_info(id, "devices");
        $("#deviceGroupBtn").remove();
        var strBtn = '<button type="button" id="deviceGroupBtn" onclick="javascript: go_device_group_update('+id+');" class="btn_success">저 장</button>';
        $(".btn_append").append(strBtn);
        //$("form[name=deviceGroupForm]").append(strBtn);
    }
}

function deviceInsertReset() {
    $("#deviceScanForm").attr("onclick","javascript: deviceSearch();");
    $("#deviceScanForm").removeClass("searchingEnd");
    let str = '<tr><td style="border: 0;"></td></tr>';
    str += '<tr><td style="border: 0;"></td></tr>';
    str += '<tr><td style="border: 0;"></td></tr>';

    str += '<tr>';
    str += '<td style="border: 0;"></td>';
    str += '<td style="border: 0;"></td>';
    str += '<td style="border: 0;"></td>';
    str += '<td style="border: 0;"></td>';
    str += '<td style="border: 0;"></td>';
    str += '<td class="py-2" style="border: 0;">';
    str += '검 색';
    str += '</td>';
	str += '<td style="border: 0;"></td>';

    str += '</tr>';

    $("#device-scan-content").empty();
    $("#device-scan-content").append(str);
    $("#device-scan-seraching").hide();
    $("#device-scan-content").show();
   
	$("#device-scan-total-row").html("");
    
    var f = document.device_insert_form;
    f.ip_addr.value = "";
    f.tcp_port.value = "";
    f.web_port.value = "";
    f.deviceName.value = "";
    f.account_id.value = "";
    f.account_pw.value = "";
    f.link_name.value = "";
    f.link_rtsp_url.value = "";
    f.link_uid.value = "";
    f.link_password.value = "";
    f.model_name.value = "";
}
function deviceSearch() {
    $("#deviceScanForm").attr("onclick","");
    $("#deviceScanForm").addClass("searchingEnd");
    device_scan("","scan",search_time);
}

//출입그룹 업데이트 전 데이터조회
function access_group_update(id) {

    $("input[name=device_ids]").prop('checked',false);

    $.ajax({
        type : "GET",
        url : ip + "/v1/access-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            
            if (data.error_code == 1) {
                $(".view_title").text("출입 그룹 수정");
                let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
                $(".card-title").append(closebtn);
                $("#typeInsertBtn").remove();
                $("#typeUpdateBtn").remove();
                let btn = '<button type="button" id="typeUpdateBtn" onclick="javascript: go_acc_group_update('+id+');" style="margin-top: 5%;" class="btn_success">저 장</button>';
                //$("form[name='access_insert_form']").append(btn);
                $('.btn_append').append(btn);
                let f = document.access_insert_form;
                f.access_name.value = data.access_group_info.name;
                f.bez_date.value = data.access_group_info.bez_date;
                f.end_date.value = data.access_group_info.end_date;
                f.acc_time_group_id.value = data.access_group_info.acc_time_group_id;
                //console.log(data.access_group_info.device_ids[0]);
                var device_chk = $('#access_insert_form').find('#device_ids');
                console.log(device_chk);
                 for(i=0; i<device_chk.length; i++){
                $("input[name=device_ids][value="+data.access_group_info.device_ids[i]+"]").prop('checked',true);   
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
//출입시간그룹 업데이트 전 데이터조회
function access_time_group_update(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-time-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
        $('.access-time-title').text("출입 시간 그룹 수정");
        
        if (data.error_code == 1) {
            $('#acc_time_group_name').val(data.access_time_group_info.name);
            
           let mon_time_start = Array();
           let mon_time_end = Array();
            
           let tue_time_start = Array();
           let tue_time_end = Array();

           let  wed_time_start = Array();
           let  wed_time_end = Array();
            
           let  thu_time_start = Array();
           let thu_time_end = Array();

           let  fri_time_start = Array();
           let  fri_time_end = Array();

           let sat_time_start = Array();
           let sat_time_end = Array();

           let sun_time_start = Array();
           let sun_time_end = Array();

            mon_time_start[1] = String(data.access_time_group_info.acc_week.mon_time_1 >> 16);
            mon_time_start[2] = String(data.access_time_group_info.acc_week.mon_time_2 >> 16);
            mon_time_start[3] = String(data.access_time_group_info.acc_week.mon_time_3 >> 16);
            mon_time_start[4] = String(data.access_time_group_info.acc_week.mon_time_4 >> 16);
           

            mon_time_end[1] = String((data.access_time_group_info.acc_week.mon_time_1) - (mon_time_start[1] << 16));
            mon_time_end[2] = String((data.access_time_group_info.acc_week.mon_time_2) - (mon_time_start[2] << 16));
            mon_time_end[3] = String((data.access_time_group_info.acc_week.mon_time_3) - (mon_time_start[3] << 16));
            mon_time_end[4] = String((data.access_time_group_info.acc_week.mon_time_4) - (mon_time_start[4] << 16));
            
            tue_time_start[1] = String(data.access_time_group_info.acc_week.tue_time_1 >> 16);
            tue_time_start[2] = String(data.access_time_group_info.acc_week.tue_time_2 >> 16);
            tue_time_start[3] = String(data.access_time_group_info.acc_week.tue_time_3 >> 16);
            tue_time_start[4] = String(data.access_time_group_info.acc_week.tue_time_4 >> 16);
            
            tue_time_end[1] = String((data.access_time_group_info.acc_week.tue_time_1) - (tue_time_start[1] << 16));
            tue_time_end[2] = String((data.access_time_group_info.acc_week.tue_time_2) - (tue_time_start[2] << 16));
            tue_time_end[3] = String((data.access_time_group_info.acc_week.tue_time_3) - (tue_time_start[3] << 16));
            tue_time_end[4] = String((data.access_time_group_info.acc_week.tue_time_4) - (tue_time_start[4] << 16));

            wed_time_start[1] = String(data.access_time_group_info.acc_week.wed_time_1 >> 16);
            wed_time_start[2] = String(data.access_time_group_info.acc_week.wed_time_2 >> 16);
            wed_time_start[3] = String(data.access_time_group_info.acc_week.wed_time_3 >> 16);
            wed_time_start[4] = String(data.access_time_group_info.acc_week.wed_time_4 >> 16);
            
            wed_time_end[1] = String((data.access_time_group_info.acc_week.wed_time_1) - (wed_time_start[1] << 16));
            wed_time_end[2] = String((data.access_time_group_info.acc_week.wed_time_2) - (wed_time_start[2] << 16));
            wed_time_end[3] = String((data.access_time_group_info.acc_week.wed_time_3) - (wed_time_start[3] << 16));
            wed_time_end[4] = String((data.access_time_group_info.acc_week.wed_time_4) - (wed_time_start[4] << 16));

            thu_time_start[1] = String(data.access_time_group_info.acc_week.thu_time_1 >> 16);
            thu_time_start[2] = String(data.access_time_group_info.acc_week.thu_time_2 >> 16);
            thu_time_start[3] = String(data.access_time_group_info.acc_week.thu_time_3 >> 16);
            thu_time_start[4] = String(data.access_time_group_info.acc_week.thu_time_4 >> 16);
            
            thu_time_end[1] = String((data.access_time_group_info.acc_week.thu_time_1) - (thu_time_start[1] << 16));
            thu_time_end[2] = String((data.access_time_group_info.acc_week.thu_time_2) - (thu_time_start[2] << 16));
            thu_time_end[3] = String((data.access_time_group_info.acc_week.thu_time_3) - (thu_time_start[3] << 16));
            thu_time_end[4] = String((data.access_time_group_info.acc_week.thu_time_4) - (thu_time_start[4] << 16));


            fri_time_start[1] = String(data.access_time_group_info.acc_week.fri_time_1 >> 16);
            fri_time_start[2] = String(data.access_time_group_info.acc_week.fri_time_2 >> 16);
            fri_time_start[3] = String(data.access_time_group_info.acc_week.fri_time_3 >> 16);
            fri_time_start[4] = String(data.access_time_group_info.acc_week.fri_time_4 >> 16);
            
            fri_time_end[1] = String((data.access_time_group_info.acc_week.fri_time_1) - (fri_time_start[1] << 16));
            fri_time_end[2] = String((data.access_time_group_info.acc_week.fri_time_2) - (fri_time_start[2] << 16));
            fri_time_end[3] = String((data.access_time_group_info.acc_week.fri_time_3) - (fri_time_start[3] << 16));
            fri_time_end[4] = String((data.access_time_group_info.acc_week.fri_time_4) - (fri_time_start[4] << 16));

            sat_time_start[1] = String(data.access_time_group_info.acc_week.sat_time_1 >> 16);
            sat_time_start[2] = String(data.access_time_group_info.acc_week.sat_time_2 >> 16);
            sat_time_start[3] = String(data.access_time_group_info.acc_week.sat_time_3 >> 16);
            sat_time_start[4] = String(data.access_time_group_info.acc_week.sat_time_4 >> 16);
            
            sat_time_end[1] = String((data.access_time_group_info.acc_week.sat_time_1) - (sat_time_start[1] << 16));
            sat_time_end[2] = String((data.access_time_group_info.acc_week.sat_time_2) - (sat_time_start[2] << 16));
            sat_time_end[3] = String((data.access_time_group_info.acc_week.sat_time_3) - (sat_time_start[3] << 16));
            sat_time_end[4] = String((data.access_time_group_info.acc_week.sat_time_4) - (sat_time_start[4] << 16));

            sun_time_start[1] = String(data.access_time_group_info.acc_week.sun_time_1 >> 16);
            sun_time_start[2] = String(data.access_time_group_info.acc_week.sun_time_2 >> 16);
            sun_time_start[3] = String(data.access_time_group_info.acc_week.sun_time_3 >> 16);
            sun_time_start[4] = String(data.access_time_group_info.acc_week.sun_time_4 >> 16);
            
            sun_time_end[1] = String((data.access_time_group_info.acc_week.sun_time_1) - (sun_time_start[1] << 16));
            sun_time_end[2] = String((data.access_time_group_info.acc_week.sun_time_2) - (sun_time_start[2] << 16));
            sun_time_end[3] = String((data.access_time_group_info.acc_week.sun_time_3) - (sun_time_start[3] << 16));
            sun_time_end[4] = String((data.access_time_group_info.acc_week.sun_time_4) - (sun_time_start[4] << 16));

            for(let i=1; i<=4; i++){

            if(mon_time_start[i] != "0"){
                if(mon_time_start[i].length == 3){
                    mon_time_start[i] = '0'+mon_time_start[i];
                }else if(mon_time_start[i].length == 2){
                    mon_time_start[i] = '00'+mon_time_start[i];
                }else if(mon_time_start[i].length == 1){
                    mon_time_start[i] = '000'+mon_time_start[i];
                }
            }
            if(mon_time_end[i] != "0"){
                if(mon_time_end[i].length == 3){
                    mon_time_end[i] = '0'+mon_time_end[i];
                }
                else if(mon_time_end[i].length == 2){
                    mon_time_end[i] = '00'+mon_time_end[i];
                }else if(mon_time_end[i].length == 1){
                    mon_time_end[i] = '000'+mon_time_end[i];
                }
            }
            if(tue_time_start[i] != "0"){
                if(tue_time_start[i].length == 3){
                    tue_time_start[i] = '0'+tue_time_start[i];
                }
                else if(tue_time_start[i].length == 2){
                    tue_time_start[i] = '00'+tue_time_start[i];
                }else if(tue_time_start[i].length == 1){
                    tue_time_start[i] = '000'+tue_time_start[i];
                }
            }
            if(tue_time_end[i] != "0"){
                if(tue_time_end[i].length == 3){
                    tue_time_end[i] = '0'+tue_time_end[i];
                }
                else if(tue_time_end[i].length == 2){
                    tue_time_end[i] = '00'+tue_time_end[i];
                }else if(tue_time_end[i].length == 1){
                    tue_time_end[i] = '000'+tue_time_end[i];
                }
            }

            if(wed_time_start[i] != "0"){
                if(wed_time_start[i].length == 3){
                    wed_time_start[i] = '0'+wed_time_start[i];
                }else if(wed_time_start[i].length == 2){
                    wed_time_start[i] = '00'+wed_time_start[i];
                }else if(wed_time_start[i].length == 1){
                    wed_time_start[i] = '000'+wed_time_start[i];
                }
            }
            if(wed_time_end[i] != "0"){
                if(wed_time_end[i].length == 3){
                    wed_time_end[i] = '0'+wed_time_end[i];
                }else if(wed_time_end[i].length == 2){
                    wed_time_end[i] = '00'+wed_time_end[i];
                }else if(wed_time_end[i].length == 1){
                    wed_time_end[i] = '000'+wed_time_end[i];
                }
            }
            if(thu_time_start[i] != "0"){
                if(thu_time_start[i].length == 3){
                    thu_time_start[i] = '0'+thu_time_start[i];
                }else if(thu_time_start[i].length == 2){
                    thu_time_start[i] = '00'+thu_time_start[i];
                }else if(thu_time_start[i].length == 1){
                    thu_time_start[i] = '000'+thu_time_start[i];
                }
            }
            if(thu_time_end[i] != "0"){
                if(thu_time_end[i].length == 3){
                    thu_time_end[i] = '0'+thu_time_end[i];
                }else if(thu_time_end[i].length == 2){
                    thu_time_end[i] = '00'+thu_time_end[i];
                }else if(thu_time_end[i].length == 1){
                    thu_time_end[i] = '000'+thu_time_end[i];
                }
            }

            if(fri_time_start[i] != "0"){
                if(fri_time_start[i].length == 3){
                    fri_time_start[i] = '0'+fri_time_start[i];
                }else if(fri_time_start[i].length == 2){
                    fri_time_start[i] = '00'+fri_time_start[i];
                }else if(fri_time_start[i].length == 1){
                    fri_time_start[i] = '000'+fri_time_start[i];
                }
            }
            if(fri_time_end[i] != "0"){
                if(fri_time_end[i].length == 3){
                    fri_time_end[i] = '0'+fri_time_end[i];
                }else if(fri_time_end[i].length == 2){
                    fri_time_end[i] = '00'+fri_time_end[i];
                }else if(fri_time_end[i].length == 1){
                    fri_time_end[i] = '000'+fri_time_end[i];
                }
            }

            if(sat_time_start[i] != "0"){
                if(sat_time_start[i].length == 3){
                    sat_time_start[i] = '0'+sat_time_start[i];
                }else if(sat_time_start[i].length == 2){
                    sat_time_start[i] = '00'+sat_time_start[i];
                }else if(sat_time_start[i].length == 1){
                    sat_time_start[i] = '000'+sat_time_start[i];
                }
            }
            if(sat_time_end[i] != "0"){
                if(sat_time_end[i].length == 3){
                    sat_time_end[i] = '0'+sat_time_end[i];
                }else if(sat_time_end[i].length == 2){
                    sat_time_end[i] = '00'+sat_time_end[i];
                }else if(sat_time_end[i].length == 1){
                    sat_time_end[i] = '000'+sat_time_end[i];
                }
            }

            if(sun_time_start[i] != "0"){
                if(sun_time_start[i].length == 3){
                    sun_time_start[i] = '0'+sun_time_start[i];
                }else if(sun_time_start[i].length == 2){
                    sun_time_start[i] = '00'+sun_time_start[i];
                }else if(sun_time_start[i].length == 1){
                    sun_time_start[i] = '000'+sun_time_start[i];
                }
            }

            if(sun_time_end[i] != "0"){
                if(sun_time_end[i].length == 3){
                    sun_time_end[i] = '0'+sun_time_end[i];
                }else if(sun_time_end[i].length == 2){
                    sun_time_end[i] = '00'+sun_time_end[i];
                }else if(sun_time_end[i].length == 1){
                    sun_time_end[i] = '000'+sun_time_end[i];
                }
            }


                if(mon_time_start[i] != ""){
                mon_time_start[i] = mon_time_start[i].slice(0,2) + ":" + mon_time_start[i].slice(2,4);
                }
                if(mon_time_end[i] != ""){
                mon_time_end[i] = mon_time_end[i].slice(0,2) + ":" + mon_time_end[i].slice(2,4);
                }
                if(tue_time_start[i] != ""){
                tue_time_start[i] = tue_time_start[i].slice(0,2) + ":" + tue_time_start[i].slice(2,4);
                }
                if(tue_time_end[i] != ""){
                tue_time_end[i] = tue_time_end[i].slice(0,2) + ":" + tue_time_end[i].slice(2,4);
                }
                if(wed_time_start[i] != ""){
                wed_time_start[i] = wed_time_start[i].slice(0,2) + ":" + wed_time_start[i].slice(2,4);
                }
                if(wed_time_end[i] != ""){
                wed_time_end[i] = wed_time_end[i].slice(0,2) + ":" + wed_time_end[i].slice(2,4);
                }
                if(thu_time_start[i] != ""){
                thu_time_start[i] = thu_time_start[i].slice(0,2) + ":" + thu_time_start[i].slice(2,4);
                }
                if(thu_time_end[i] != ""){
                thu_time_end[i] = thu_time_end[i].slice(0,2) + ":" + thu_time_end[i].slice(2,4);
                }
                if(fri_time_start[i] != ""){
                fri_time_start[i] = fri_time_start[i].slice(0,2) + ":" + fri_time_start[i].slice(2,4);
                }
                if(fri_time_end[i] != ""){
                fri_time_end[i] = fri_time_end[i].slice(0,2) + ":" + fri_time_end[i].slice(2,4);
                }
                if(sat_time_start[i] != ""){
                sat_time_start[i] = sat_time_start[i].slice(0,2) + ":" + sat_time_start[i].slice(2,4);
                }
                if(sat_time_end[i] != ""){
                sat_time_end[i] = sat_time_end[i].slice(0,2) + ":" + sat_time_end[i].slice(2,4);
                }
                if(sun_time_start[i] != ""){
                sun_time_start[i] = sun_time_start[i].slice(0,2) + ":" + sun_time_start[i].slice(2,4);
                }
                if(sun_time_end[i] != ""){
                sun_time_end[i] = sun_time_end[i].slice(0,2) + ":" + sun_time_end[i].slice(2,4);
                }
               
                
                $(`#mon_time_${i}_start`).attr('value',mon_time_start[i]);
                $(`#mon_time_${i}_end`).attr('value',mon_time_end[i]);

                $(`#tue_time_${i}_start`).attr('value',tue_time_start[i]);
                $(`#tue_time_${i}_end`).attr('value',tue_time_end[i]);

                $(`#wed_time_${i}_start`).attr('value',wed_time_start[i]);
                $(`#wed_time_${i}_end`).attr('value',wed_time_end[i]);

                $(`#thu_time_${i}_start`).attr('value',thu_time_start[i]);
                $(`#thu_time_${i}_end`).attr('value',thu_time_end[i]);

                $(`#fri_time_${i}_start`).attr('value',fri_time_start[i]);
                $(`#fri_time_${i}_end`).attr('value',fri_time_end[i]);

                $(`#sat_time_${i}_start`).attr('value',sat_time_start[i]);
                $(`#sat_time_${i}_end`).attr('value',sat_time_end[i]);

                $(`#sun_time_${i}_start`).attr('value',sun_time_start[i]);
                $(`#sun_time_${i}_end`).attr('value',sun_time_end[i]);
            }
               
            
        //휴일 그룹 시간범위
        let holi_start_1 = Array();
        let holi_start_2 = Array();
        let holi_start_3 = Array();
        let holi_start_4 = Array();
        let holi_start_5 = Array();
        let holi_start_6 = Array();
        let holi_start_7 = Array();
        let holi_start_8 = Array();
        let holi_start_9 = Array();
        let holi_start_10 = Array();
        let holi_start_11 = Array();
        let holi_start_12 = Array();
        let holi_start_13 = Array();
        let holi_start_14 = Array();
        let holi_start_15 = Array();
        let holi_start_16 = Array();

        let holi_end_1 = Array();
        let holi_end_2 = Array();
        let holi_end_3 = Array();
        let holi_end_4 = Array();
        let holi_end_5 = Array();
        let holi_end_6 = Array();
        let holi_end_7 = Array();
        let holi_end_8 = Array();
        let holi_end_9 = Array();
        let holi_end_10 = Array();
        let holi_end_11 = Array();
        let holi_end_12 = Array();
        let holi_end_13 = Array();
        let holi_end_14 = Array();
        let holi_end_15 = Array();
        let holi_end_16 = Array();

      
            holi_start_1[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_1 >> 16);
            holi_start_1[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_2 >> 16);
            holi_start_1[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_3 >> 16);
            holi_start_1[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_4 >> 16);

            

            holi_end_1[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_1) - (holi_start_1[1] << 16));
            holi_end_1[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_2) - (holi_start_1[2] << 16));
            holi_end_1[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_3) - (holi_start_1[3] << 16));
            holi_end_1[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id1.time_4) - (holi_start_1[4] << 16));
            
            holi_start_2[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_1 >> 16);
            holi_start_2[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_2 >> 16);
            holi_start_2[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_3 >> 16);
            holi_start_2[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_4 >> 16);

            holi_end_2[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_1) - (holi_start_2[1] << 16));
            holi_end_2[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_2) - (holi_start_2[2] << 16));
            holi_end_2[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_3) - (holi_start_2[3] << 16));
            holi_end_2[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id2.time_4) - (holi_start_2[4] << 16));
        
            holi_start_3[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_1 >> 16);
            holi_start_3[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_2 >> 16);
            holi_start_3[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_3 >> 16);
            holi_start_3[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_4 >> 16);

            holi_end_3[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_1) - (holi_start_3[1] << 16));
            holi_end_3[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_2) - (holi_start_3[2] << 16));
            holi_end_3[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_3) - (holi_start_3[3] << 16));
            holi_end_3[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_4) - (holi_start_3[4] << 16));

            holi_start_4[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_1 >> 16);
            holi_start_4[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_2 >> 16);
            holi_start_4[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_3 >> 16);
            holi_start_4[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_4 >> 16);

            holi_end_4[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_1) - (holi_start_4[1] << 16));
            holi_end_4[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_2) - (holi_start_4[2] << 16));
            holi_end_4[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_3) - (holi_start_4[3] << 16));
            holi_end_4[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id4.time_4) - (holi_start_4[4] << 16));

            holi_start_5[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_5 >> 16);
            holi_start_5[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_5 >> 16);
            holi_start_5[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_5 >> 16);
            holi_start_5[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.time_5 >> 16);

            holi_end_5[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id5.time_1) - (holi_start_5[1] << 16));
            holi_end_5[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id5.time_2) - (holi_start_5[2] << 16));
            holi_end_5[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id5.time_3) - (holi_start_5[3] << 16));
            holi_end_5[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id5.time_4) - (holi_start_5[4] << 16));
            
            holi_start_6[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_1 >> 16);
            holi_start_6[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_2 >> 16);
            holi_start_6[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_3 >> 16);
            holi_start_6[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_4 >> 16);

            holi_end_6[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_1) - (holi_start_6[1] << 16));
            holi_end_6[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_2) - (holi_start_6[2] << 16));
            holi_end_6[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_3) - (holi_start_6[3] << 16));
            holi_end_6[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id6.time_4) - (holi_start_6[4] << 16));

            holi_start_7[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_1 >> 16);
            holi_start_7[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_2 >> 16);
            holi_start_7[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_3 >> 16);
            holi_start_7[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_4 >> 16);

            holi_end_7[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_1) - (holi_start_7[1] << 16));
            holi_end_7[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_2) - (holi_start_7[2] << 16));
            holi_end_7[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_3) - (holi_start_7[3] << 16));
            holi_end_7[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id7.time_4) - (holi_start_7[4] << 16));
          
            holi_start_8[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_1 >> 16);
            holi_start_8[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_2 >> 16);
            holi_start_8[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_3 >> 16);
            holi_start_8[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_4 >> 16);

            holi_end_8[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_1) - (holi_start_8[1] << 16));
            holi_end_8[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_2) - (holi_start_8[2] << 16));
            holi_end_8[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_3) - (holi_start_8[3] << 16));
            holi_end_8[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id8.time_4) - (holi_start_8[4] << 16));

            holi_start_9[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_1 >> 16);
            holi_start_9[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_2 >> 16);
            holi_start_9[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_3 >> 16);
            holi_start_9[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_4 >> 16);

            holi_end_9[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_1) - (holi_start_9[1] << 16));
            holi_end_9[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_2) - (holi_start_9[2] << 16));
            holi_end_9[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_3) - (holi_start_9[3] << 16));
            holi_end_9[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id9.time_4) - (holi_start_9[4] << 16));
        
            holi_start_10[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_1 >> 16);
            holi_start_10[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_2 >> 16);
            holi_start_10[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_3 >> 16);
            holi_start_10[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_4 >> 16);

            holi_end_10[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_1) - (holi_start_10[1] << 16));
            holi_end_10[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_2) - (holi_start_10[2] << 16));
            holi_end_10[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_3) - (holi_start_10[3] << 16));
            holi_end_10[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id10.time_4) - (holi_start_10[4] << 16));

            holi_start_11[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_1 >> 16);
            holi_start_11[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_2 >> 16);
            holi_start_11[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_3 >> 16);
            holi_start_11[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_4 >> 16);

            holi_end_11[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_1) - (holi_start_11[1] << 16));
            holi_end_11[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_2) - (holi_start_11[2] << 16));
            holi_end_11[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_3) - (holi_start_11[3] << 16));
            holi_end_11[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id11.time_4) - (holi_start_11[4] << 16));

            holi_start_12[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_1 >> 16);
            holi_start_12[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_2 >> 16);
            holi_start_12[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_3 >> 16);
            holi_start_12[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_4 >> 16);

            holi_end_12[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_1) - (holi_start_12[1] << 16));
            holi_end_12[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_2) - (holi_start_12[2] << 16));
            holi_end_12[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_3) - (holi_start_12[3] << 16));
            holi_end_12[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id12.time_4) - (holi_start_12[4] << 16));

            holi_start_13[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_1 >> 16);
            holi_start_13[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_2 >> 16);
            holi_start_13[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_3 >> 16);
            holi_start_13[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_4 >> 16);

            holi_end_13[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_1) - (holi_start_13[1] << 16));
            holi_end_13[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_2) - (holi_start_13[2] << 16));
            holi_end_13[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_3) - (holi_start_13[3] << 16));
            holi_end_13[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id13.time_4) - (holi_start_13[4] << 16));
            
            holi_start_14[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_1 >> 16);
            holi_start_14[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_2 >> 16);
            holi_start_14[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_3 >> 16);
            holi_start_14[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_4 >> 16);

            holi_end_14[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_1) - (holi_start_14[1] << 16));
            holi_end_14[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_2) - (holi_start_14[2] << 16));
            holi_end_14[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_3) - (holi_start_14[3] << 16));
            holi_end_14[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id14.time_4) - (holi_start_14[4] << 16));
            
            holi_start_15[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_1 >> 16);
            holi_start_15[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_2 >> 16);
            holi_start_15[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_3 >> 16);
            holi_start_15[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_4 >> 16);

            holi_end_15[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_1) - (holi_start_15[1] << 16));
            holi_end_15[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_2) - (holi_start_15[2] << 16));
            holi_end_15[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_3) - (holi_start_15[3] << 16));
            holi_end_15[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id15.time_4) - (holi_start_15[4] << 16));

            holi_start_16[1] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_1 >> 16);
            holi_start_16[2] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_2 >> 16);
            holi_start_16[3] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_3 >> 16);
            holi_start_16[4] = String(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_4 >> 16);

            holi_end_16[1] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_1) - (holi_start_16[1] << 16));
            holi_end_16[2] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_2) - (holi_start_16[2] << 16));
            holi_end_16[3] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_3) - (holi_start_16[3] << 16));
            holi_end_16[4] = String((data.access_time_group_info.acc_holiday_group.hol_cfg_id16.time_4) - (holi_start_16[4] << 16));
            
            for(let i=1; i<=4; i++){
                
            if(holi_start_1[i] != "0"){
                if(holi_start_1[i].length == 3){
                    holi_start_1[i] = "0"+holi_start_1[i];
                }
                else if(holi_start_1[i].length == 2){
                    holi_start_1[i] = '00'+holi_start_1[i];
                }else if(holi_start_1[i].length == 1){
                    holi_start_1[i] = '000'+holi_start_1[i];
                }
            }
            if(holi_start_2[i] != "0"){
                if(holi_start_2[i].length == 3){
                    holi_start_2[i] = "0"+holi_start_2[i];
                }
                else if(holi_start_2[i].length == 2){
                    holi_start_2[i] = '00'+holi_start_2[i];
                }else if(holi_start_2[i].length == 1){
                    holi_start_2[i] = '000'+holi_start_2[i];
                }
            }
            if(holi_start_3[i] != "0"){
                if(holi_start_3[i].length == 3){
                    holi_start_3[i] = "0"+holi_start_3[i];
                }
                else if(holi_start_3[i].length == 2){
                    holi_start_3[i] = '00'+holi_start_3[i];
                }else if(holi_start_3[i].length == 1){
                    holi_start_3[i] = '000'+holi_start_3[i];
                }
            }
            if(holi_start_4[i] != "0"){
                if(holi_start_4[i].length == 3){
                    holi_start_4[i] = "0"+holi_start_4[i];
                }else if(holi_start_4[i].length == 2){
                    holi_start_4[i] = '00'+holi_start_4[i];
                }else if(holi_start_4[i].length == 1){
                    holi_start_4[i] = '000'+holi_start_4[i];
                }
            }
            if(holi_start_5[i] != "0"){
                if(holi_start_5[i].length == 3){
                    holi_start_5[i] = "0"+holi_start_5[i];
                }else if(holi_start_5[i].length == 2){
                    holi_start_5[i] = '00'+holi_start_5[i];
                }else if(holi_start_5[i].length == 1){
                    holi_start_5[i] = '000'+holi_start_5[i];
                }
            }
            if(holi_start_6[i] != "0"){
                if(holi_start_6[i].length == 3){
                    holi_start_6[i] = "0"+holi_start_6[i];
                }else if(holi_start_6[i].length == 2){
                    holi_start_6[i] = '00'+holi_start_6[i];
                }else if(holi_start_6[i].length == 1){
                    holi_start_6[i] = '000'+holi_start_6[i];
                }
            }
            if(holi_start_7[i] != "0"){
                if(holi_start_7[i].length == 3){
                    holi_start_7[i] =   "0"+holi_start_7[i];
                }else if(holi_start_7[i].length == 2){
                    holi_start_7[i] = '00'+holi_start_7[i];
                }else if(holi_start_7[i].length == 1){
                    holi_start_7[i] = '000'+holi_start_7[i];
                }
            }
            if(holi_start_8[i] != "0"){
                if(holi_start_8[i].length == 3){
                    holi_start_8[i] =   "0"+holi_start_8[i];
                }
                else if(holi_start_8[i].length == 2){
                    holi_start_8[i] = '00'+holi_start_8[i];
                }else if(holi_start_8[i].length == 1){
                    holi_start_8[i] = '000'+holi_start_8[i];
                }
            }
            if(holi_start_9[i] != "0"){
                if(holi_start_9[i].length == 3){
                    holi_start_9[i] =   "0"+holi_start_9[i];
                }else if(holi_start_9[i].length == 2){
                    holi_start_9[i] = '00'+holi_start_9[i];
                }else if(holi_start_9[i].length == 1){
                    holi_start_9[i] = '000'+holi_start_9[i];
                }
            }
            if(holi_start_10[i] != "0"){
                if(holi_start_10[i].length == 3){
                    holi_start_10[i] =  "0"+holi_start_10[i];
                }
                else if(holi_start_10[i].length == 2){
                    holi_start_10[i] = '00'+holi_start_10[i];
                }else if(holi_start_10[i].length == 1){
                    holi_start_10[i] = '000'+holi_start_10[i];
                }
            }

            if(holi_start_11[i] != "0"){
                if(holi_start_11[i].length == 3){
                    holi_start_11[i] =  "0"+holi_start_11[i];
                }
                else if(holi_start_11[i].length == 2){
                    holi_start_11[i] = '00'+holi_start_11[i];
                }else if(holi_start_11[i].length == 1){
                    holi_start_11[i] = '000'+holi_start_11[i];
                }
            }

            if(holi_start_12[i] != "0"){
                if(holi_start_12[i].length == 3){
                    holi_start_12[i] =   "0"+holi_start_12[i];
                }else if(holi_start_12[i].length == 2){
                    holi_start_12[i] = '00'+holi_start_12[i];
                }else if(holi_start_12[i].length == 1){
                    holi_start_12[i] = '000'+holi_start_12[i];
                }
            }

            if(holi_start_13[i] != "0"){
                if(holi_start_13[i].length == 3){
                    holi_start_13[i] =   "0"+holi_start_13[i];
                }else if(holi_start_13[i].length == 2){
                    holi_start_13[i] = '00'+holi_start_13[i];
                }else if(holi_start_13[i].length == 1){
                    holi_start_13[i] = '000'+holi_start_13[i];
                }
            }
            if(holi_start_14[i] != "0"){
                if(holi_start_14[i].length == 3){
                    holi_start_14[i] =  "0"+holi_start_14[i];
                }else if(holi_start_14[i].length == 2){
                    holi_start_14[i] = '00'+holi_start_14[i];
                }else if(holi_start_14[i].length == 1){
                    holi_start_14[i] = '000'+holi_start_14[i];
                }
            }

            if(holi_start_15[i] != "0"){
                if(holi_start_15[i].length == 3){
                    holi_start_15[i] =  "0"+holi_start_15[i];
                }else if(holi_start_15[i].length == 2){
                    holi_start_15[i] = '00'+holi_start_15[i];
                }else if(holi_start_15[i].length == 1){
                    holi_start_15[i] = '000'+holi_start_15[i];
                }
            }

            if(holi_start_16[i] != "0"){
                if(holi_start_16[i].length == 3){
                    holi_start_16[i] =   "0"+holi_start_16[i];
                }else if(holi_start_16[i].length == 2){
                    holi_start_16[i] = '00'+holi_start_16[i];
                }else if(holi_start_16[i].length == 1){
                    holi_start_16[i] = '000'+holi_start_16[i];
                }
            }

            if(holi_end_1[i] != "0"){
                if(holi_end_1[i].length == 3){
                    holi_end_1[i] = "0"+holi_end_1[i];
                }else if(holi_end_1[i].length == 2){
                    holi_end_1[i] = '00'+holi_end_1[i];
                }else if(holi_end_1[i].length == 1){
                    holi_end_1[i] = '000'+holi_end_1[i];
                }
            }
            if(holi_end_2[i] != "0"){
                if(holi_end_2[i].length == 3){
                    holi_end_2[i] = "0"+holi_end_2[i];
                }else if(holi_end_2[i].length == 2){
                    holi_end_2[i] = '00'+holi_end_2[i];
                }else if(holi_end_2[i].length == 1){
                    holi_end_2[i] = '000'+holi_end_2[i];
                }
            }
            
            if(holi_end_3[i] != "0"){
                if(holi_end_3[i].length == 3){
                    holi_end_3[i] = "0"+holi_end_3[i];
                }
                else if(holi_end_3[i].length == 2){
                    holi_end_3[i] = '00'+holi_end_3[i];
                }else if(holi_end_3[i].length == 1){
                    holi_end_3[i] = '000'+holi_end_3[i];
                }
            }
            if(holi_end_4[i] != "0"){
                if(holi_end_4[i].length == 3){
                    holi_end_4[i] =  "0"+holi_end_4[i];
                }else if(holi_end_4[i].length == 2){
                    holi_end_4[i] = '00'+holi_end_4[i];
                }else if(holi_end_4[i].length == 1){
                    holi_end_4[i] = '000'+holi_end_4[i];
                }
            }

            if(holi_end_5[i] != "0"){
                if(holi_end_5[i].length == 3){
                    holi_end_5[i] =  "0"+holi_end_5[i];
                }
                else if(holi_end_5[i].length == 2){
                    holi_end_5[i] = '00'+holi_end_5[i];
                }else if(holi_end_5[i].length == 1){
                    holi_end_5[i] = '000'+holi_end_5[i];
                }
            }

            if(holi_end_6[i] != "0"){
                if(holi_end_6[i].length == 3){
                    holi_end_6[i] =   "0"+holi_end_6[i];
                }else if(holi_end_6[i].length == 2){
                    holi_end_6[i] = '00'+holi_end_6[i];
                }else if(holi_end_6[i].length == 1){
                    holi_end_6[i] = '000'+holi_end_6[i];
                }
            }

            if(holi_end_7[i] != "0"){
                if(holi_end_7[i].length == 3){
                    holi_end_7[i] = "0"+holi_end_7[i];
                }else if(holi_end_7[i].length == 2){
                    holi_end_7[i] = '00'+holi_end_7[i];
                }else if(holi_end_7[i].length == 1){
                    holi_end_7[i] = '000'+holi_end_7[i];
                }
            }

            if(holi_end_8[i] != "0"){
                if(holi_end_8[i].length == 3){
                    holi_end_8[i] = "0"+holi_end_8[i];
                }else if(holi_end_8[i].length == 2){
                    holi_end_8[i] = '00'+holi_end_8[i];
                }else if(holi_end_8[i].length == 1){
                    holi_end_8[i] = '000'+holi_end_8[i];
                }
            }

            if(holi_end_9[i] != "0"){
                if(holi_end_9[i].length == 3){
                    holi_end_9[i] =  "0"+holi_end_9[i];
                }else if(holi_end_9[i].length == 2){
                    holi_end_9[i] = '00'+holi_end_9[i];
                }else if(holi_end_9[i].length == 1){
                    holi_end_9[i] = '000'+holi_end_9[i];
                }
            }

            if(holi_end_10[i] != "0"){
                if(holi_end_10[i].length == 3){
                    holi_end_10[i] =   "0"+holi_end_10[i];
                }else if(holi_end_10[i].length == 2){
                    holi_end_10[i] = '00'+holi_end_10[i];
                }else if(holi_end_10[i].length == 1){
                    holi_end_10[i] = '000'+holi_end_10[i];
                }
            }

            if(holi_end_11[i] != "0"){
                if(holi_end_11[i].length == 3){
                    holi_end_11[i] =  "0"+holi_end_11[i];
                }else if(holi_end_11[i].length == 2){
                    holi_end_11[i] = '00'+holi_end_11[i];
                }else if(holi_end_11[i].length == 1){
                    holi_end_11[i] = '000'+holi_end_11[i];
                }
            }

            if(holi_end_12[i] != "0"){
                if(holi_end_12[i].length == 3){
                    holi_end_12[i] = "0"+holi_end_12[i];
                }else if(holi_end_12[i].length == 2){
                    holi_end_12[i] = '00'+holi_end_12[i];
                }else if(holi_end_12[i].length == 1){
                    holi_end_12[i] = '000'+holi_end_12[i];
                }
            }

            if(holi_end_13[i] != "0"){
                if(holi_end_13[i].length == 3){
                    holi_end_13[i] = "0"+holi_end_13[i];
                }else if(holi_end_13[i].length == 2){
                    holi_end_13[i] = '00'+holi_end_13[i];
                }else if(holi_end_13[i].length == 1){
                    holi_end_13[i] = '000'+holi_end_13[i];
                }
            }

            if(holi_end_14[i] != "0"){
                if(holi_end_14[i].length == 3){
                    holi_end_14[i] = "0"+holi_end_14[i];
                }
                else if(holi_end_14[i].length == 2){
                    holi_end_14[i] = '00'+holi_end_14[i];
                }else if(holi_end_14[i].length == 1){
                    holi_end_14[i] = '000'+holi_end_14[i];
                }
            }

            if(holi_end_15[i] != "0"){
                if(holi_end_15[i].length == 3){
                    holi_end_15[i] = "0"+holi_end_15[i];
                }
                else if(holi_end_15[i].length == 2){
                    holi_end_15[i] = '00'+holi_end_15[i];
                }else if(holi_end_15[i].length == 1){
                    holi_end_15[i] = '000'+holi_end_15[i];
                }
            }

            if(holi_end_16[i] != "0"){
                if(holi_end_16[i].length == 3){
                    holi_end_16[i] = "0"+holi_end_16[i];
                }
                else if(holi_end_16[i].length == 2){
                    holi_end_16[i] = '00'+holi_end_16[i];
                }else if(holi_end_16[i].length == 1){
                    holi_end_16[i] = '000'+holi_end_16[i];
                }
            }
                holi_start_1[i] = holi_start_1[i].slice(0,2) + ":" + holi_start_1[i].slice(2,4);
                holi_end_1[i] = holi_end_1[i].slice(0,2) + ":" + holi_end_1[i].slice(2,4);

                holi_start_2[i] = holi_start_2[i].slice(0,2) + ":" + holi_start_2[i].slice(2,4);
                holi_end_2[i] = holi_end_2[i].slice(0,2) + ":" + holi_end_2[i].slice(2,4);

                holi_start_3[i] = holi_start_3[i].slice(0,2) + ":" + holi_start_3[i].slice(2,4);
                holi_end_3[i] = holi_end_3[i].slice(0,2) + ":" + holi_end_3[i].slice(2,4);

                holi_start_4[i] = holi_start_4[i].slice(0,2) + ":" + holi_start_4[i].slice(2,4);
                holi_end_4[i] = holi_end_4[i].slice(0,2) + ":" + holi_end_4[i].slice(2,4);

                holi_start_5[i] = holi_start_5[i].slice(0,2) + ":" + holi_start_5[i].slice(2,4);
                holi_end_5[i] = holi_end_5[i].slice(0,2) + ":" + holi_end_5[i].slice(2,4);
                
                holi_start_6[i] = holi_start_6[i].slice(0,2) + ":" + holi_start_6[i].slice(2,4);
                holi_end_6[i] = holi_end_6[i].slice(0,2) + ":" + holi_end_6[i].slice(2,4);
                
                holi_start_7[i] = holi_start_7[i].slice(0,2) + ":" + holi_start_7[i].slice(2,4);
                holi_end_7[i] = holi_end_7[i].slice(0,2) + ":" + holi_end_7[i].slice(2,4);

                holi_start_8[i] = holi_start_8[i].slice(0,2) + ":" + holi_start_8[i].slice(2,4);
                holi_end_8[i] = holi_end_8[i].slice(0,2) + ":" + holi_end_8[i].slice(2,4);

                holi_start_9[i] = holi_start_9[i].slice(0,2) + ":" + holi_start_9[i].slice(2,4);
                holi_end_9[i] = holi_end_9[i].slice(0,2) + ":" + holi_end_9[i].slice(2,4);

                holi_start_10[i] = holi_start_10[i].slice(0,2) + ":" + holi_start_10[i].slice(2,4);
                holi_end_10[i] = holi_end_10[i].slice(0,2) + ":" + holi_end_10[i].slice(2,4);

                holi_start_11[i] = holi_start_11[i].slice(0,2) + ":" + holi_start_11[i].slice(2,4);
                holi_end_11[i] = holi_end_11[i].slice(0,2) + ":" + holi_end_11[i].slice(2,4);

                holi_start_12[i] = holi_start_12[i].slice(0,2) + ":" + holi_start_12[i].slice(2,4);
                holi_end_12[i] = holi_end_12[i].slice(0,2) + ":" + holi_end_12[i].slice(2,4);

                holi_start_13[i] = holi_start_13[i].slice(0,2) + ":" + holi_start_13[i].slice(2,4);
                holi_end_13[i] = holi_end_13[i].slice(0,2) + ":" + holi_end_13[i].slice(2,4);

                holi_start_14[i] = holi_start_14[i].slice(0,2) + ":" + holi_start_14[i].slice(2,4);
                holi_end_14[i] = holi_end_14[i].slice(0,2) + ":" + holi_end_14[i].slice(2,4);

                holi_start_15[i] = holi_start_15[i].slice(0,2) + ":" + holi_start_15[i].slice(2,4);
                holi_end_15[i] = holi_end_15[i].slice(0,2) + ":" + holi_end_15[i].slice(2,4);

                holi_start_16[i] = holi_start_16[i].slice(0,2) + ":" + holi_start_16[i].slice(2,4);
                holi_end_16[i] = holi_end_16[i].slice(0,2) + ":" + holi_end_16[i].slice(2,4);
               
              
               
                $(`#hol_cfg1_start${i}`).attr('value',holi_start_1[i]);
                $(`#hol_cfg1_end${i}`).attr('value',holi_end_1[i]);

                $(`#hol_cfg2_start${i}`).attr('value',holi_start_2[i]);
                $(`#hol_cfg2_end${i}`).attr('value',holi_end_2[i]);
            
                $(`#hol_cfg3_start${i}`).attr('value',holi_start_3[i]);
                $(`#hol_cfg3_end${i}`).attr('value',holi_end_3[i]);
                
                $(`#hol_cfg4_start${i}`).attr('value',holi_start_4[i]);
                $(`#hol_cfg4_end${i}`).attr('value',holi_end_14[i]);

                $(`#hol_cfg5_start${i}`).attr('value',holi_start_5[i]);
                $(`#hol_cfg5_end${i}`).attr('value',holi_end_5[i]);

                $(`#hol_cfg6_start${i}`).attr('value',holi_start_6[i]);
                $(`#hol_cfg6_end${i}`).attr('value',holi_end_6[i]);

                $(`#hol_cfg7_start${i}`).attr('value',holi_start_7[i]);
                $(`#hol_cfg7_end${i}`).attr('value',holi_end_7[i]);

                $(`#hol_cfg8_start${i}`).attr('value',holi_start_8[i]);
                $(`#hol_cfg8_end${i}`).attr('value',holi_end_8[i]);

                $(`#hol_cfg9_start${i}`).attr('value',holi_start_9[i]);
                $(`#hol_cfg9_end${i}`).attr('value',holi_end_9[i]);

                $(`#hol_cfg10_start${i}`).attr('value',holi_start_10[i]);
                $(`#hol_cfg10_end${i}`).attr('value',holi_end_10[i]);

                $(`#hol_cfg11_start${i}`).attr('value',holi_start_11[i]);
                $(`#hol_cfg11_end${i}`).attr('value',holi_end_11[i]);

                $(`#hol_cfg12_start${i}`).attr('value',holi_start_12[i]);
                $(`#hol_cfg12_end${i}`).attr('value',holi_end_12[i]);

                $(`#hol_cfg13_start${i}`).attr('value',holi_start_13[i]);
                $(`#hol_cfg13_end${i}`).attr('value',holi_end_13[i]);

                $(`#hol_cfg14_start${i}`).attr('value',holi_start_14[i]);
                $(`#hol_cfg14_end${i}`).attr('value',holi_end_14[i]);

                $(`#hol_cfg15_start${i}`).attr('value',holi_start_15[i]);
                $(`#hol_cfg15_end${i}`).attr('value',holi_end_15[i]);

                $(`#hol_cfg16_start${i}`).attr('value',holi_start_16[i]);
                $(`#hol_cfg16_end${i}`).attr('value',holi_end_16[i]);

                $('#hol_cfg1_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.name);
                $('#hol_cfg1_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.bez_date);
                $('#hol_cfg1_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id1.end_date);

                $('#hol_cfg2_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.name);
                $('#hol_cfg2_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.bez_date);
                $('#hol_cfg2_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.end_date);

                $('#hol_cfg3_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.name);
                $('#hol_cfg3_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.bez_date);
                $('#hol_cfg3_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.end_date);

                $('#hol_cfg4_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.name);
                $('#hol_cfg4_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.bez_date);
                $('#hol_cfg4_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.end_date);

                $('#hol_cfg5_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id5.name);
                $('#hol_cfg5_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id5.bez_date);
                $('#hol_cfg5_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id5.end_date);

                $('#hol_cfg6_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.name);
                $('#hol_cfg6_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.bez_date);
                $('#hol_cfg6_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.end_date);

                $('#hol_cfg7_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.name);
                $('#hol_cfg7_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.bez_date);
                $('#hol_cfg7_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.end_date);

                $('#hol_cfg8_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.name);
                $('#hol_cfg8_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.bez_date);
                $('#hol_cfg8_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.end_date);

                $('#hol_cfg9_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.name);
                $('#hol_cfg9_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.bez_date);
                $('#hol_cfg9_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.end_date);

                $('#hol_cfg10_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.name);
                $('#hol_cfg10_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.bez_date);
                $('#hol_cfg10_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.end_date);

                $('#hol_cfg11_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.name);
                $('#hol_cfg11_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.bez_date);
                $('#hol_cfg11_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.end_date);

                $('#hol_cfg12_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.name);
                $('#hol_cfg12_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.bez_date);
                $('#hol_cfg12_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.end_date);

                $('#hol_cfg13_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.name);
                $('#hol_cfg13_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.bez_date);
                $('#hol_cfg13_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.end_date);

                $('#hol_cfg14_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.name);
                $('#hol_cfg14_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.bez_date);
                $('#hol_cfg14_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.end_date);

                $('#hol_cfg15_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.name);
                $('#hol_cfg15_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.bez_date);
                $('#hol_cfg15_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.end_date);

                $('#hol_cfg16_name').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.name);
                $('#hol_cfg16_bez_day').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.bez_date);
                $('#hol_cfg16_end_date').val(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.end_date);

            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id2.name != ""){
                $('#hol_cfg_id2').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id3.name != ""){
                $('#hol_cfg_id3').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id4.name != ""){
                $('#hol_cfg_id4').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id5.name != ""){
                $('#hol_cfg_id5').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id6.name != ""){
                $('#hol_cfg_id6').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id7.name != ""){
                $('#hol_cfg_id7').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id8.name != ""){
                $('#hol_cfg_id8').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id9.name != ""){
                $('#hol_cfg_id9').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id10.name != ""){
                $('#hol_cfg_id10').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id11.name != ""){
                $('#hol_cfg_id11').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id12.name != ""){
                $('#hol_cfg_id12').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id13.name != ""){
                $('#hol_cfg_id13').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id14.name != ""){
                $('#hol_cfg_id14').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id15.name != ""){
                $('#hol_cfg_id15').removeClass('displayHide');
            }
            if(data.access_time_group_info.acc_holiday_group.hol_cfg_id16.name != ""){
                $('#hol_cfg_id16').removeClass('displayHide');
            }
         
            }
            $('.btn-block').attr('onclick',"");
            $('.btn-block').attr('onclick',`go_acc_time_group_update2(${data.access_time_group_info.id});`);
                console.log(data);       
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
//주간일정 업데이트 전 데이터조회
function access_week_update(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-weeks/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                $("#accWeekModal .card-title").text("주간 일정 수정");
                let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
                $(".card-title").append(closebtn);
                $("#typeInsertBtn").remove();
                $("#typeUpdateBtn").remove();
                let btn = '<button type="button" id="typeUpdateBtn" onclick="javascript:go_acc_week_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='acc_week_insert_form']").append(btn);
                let f = document.acc_week_insert_form;
                f.access_week_name.value = data.access_week_info.name;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
//휴일그룹 업데이트 전 데이터조회
function access_holiday_group_update(id) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-holiday-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            console.log(data);
            if (data.error_code == 1) {
                $("#theModal .card-title").text("휴일 그룹 수정");
                let closebtn = '<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>';
                $(".card-title").append(closebtn);
                $("#typeInsertBtn").remove();
                $("#typeUpdateBtn").remove();
                let btn = '<button type="button" id="typeUpdateBtn" onclick="javascript:go_acc_holiday_group_update('+id+');" class="btn btn-primary btn-block">저 장</button>';
                $("form[name='acc_holiday_group_form']").append(btn);
                let f = document.acc_holiday_group_form;
                f.access_holiday_group_name.value = data.access_holiday_group_info.name;
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

// function userGroupSet(job, id) {
//     var f = document.userGroupForm;
//     if (job == "add" || job == "addBtn") {
//         $("#userGroupModal .title_fonts").text('사용자 그룹 추가');
//         $("#userGroupModal .title_fonts").append('<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>');        f.parentId.value = id;
//         $("input[name=groupName]").val("");
//         $("#deviceGroupBtn").remove();
//         $("#userGroupBtn").remove();
//         var strBtn = '<button type="button" id="userGroupBtn" onclick="javascript: go_user_group_insert2(\'user-group\');" class="btn btn-primary btn-block-small">저 장</button>';
//         $("form[name=userGroupForm]").append(strBtn);
//     } else if(job == "upd") {
//        $("#userGroupModal .title_fonts").text("사용자 그룹 수정");
//         $("#userGroupModal .title_fonts").append('<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.2rem 1rem 0 0;"><span aria-hidden="true">&times;</span></button>');
//         user_group_update_info(id, "user-group");
//         $("input[name=groupName]").val("");
//         $("#deviceGroupBtn").remove();
//         $("#userGroupBtn").remove();
//         var strBtn = '<button type="button" id="deviceGroupBtn" onclick="javascript: go_user_group_update2('+id+',\'user-group\');" class="btn btn-primary btn-block-small">저 장</button>';
//         $("form[name=userGroupForm]").append(strBtn);
//     }
// }

function go_user_group_insert2(job) {
    var f = document.userGroupForm;
    if (f.groupName.value == "") {
        alert("그룹 이름을 입력해주세요."); f.group_name.focus();
        return;
    }
    var du_groupinfo = {"name": f.groupName.value, "parent_id": f.parentId.value};
    
    $.ajax({
        type : "POST",
        url : ip + "/v1/user-groups",
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_groupinfo),
        success : function(data) {
            if (data.error_code == 1) {
                alert("사용자 그룹을 추가하였습니다.");
                //refresh('users');
                $('#userGroupModal').modal('hide');

                $.ajax({
                    type:"GET",
                    url : ip + "/v1/user-groups",
                    dataType : 'json',
                    contentType : 'application/json',
                    success : function(data){
                       
                        console.log(data);
                      
                          var f = document.userGroupForm;
                            
                            var usergroupStr = "";
                            for(i=0; i<data.du_group_infos.length; i++){
                            var selected = (f.groupName.value == data.du_group_infos[i].name) ? 'selected' : '';
                            usergroupStr += "<option value='"+data.du_group_infos[i].id+"' "+selected+">"+data.du_group_infos[i].name+"</option>";
                            }
                            $("#group_id").empty();
                            $("#group_id").append(usergroupStr);
                            
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log("error");
                    }
                });
            }
            else{
                console.log(data);
                alert("사용자 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_group_update_info(id, job) {
    $.ajax({
        type : "GET",
        url : ip + "/v1/user-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        success : function(data) {
            //console.log(data);
            if (data.error_code == 1) {
                if (job == "user-group") {
                    var f = document.userGroupForm;
                    f.groupName.value = data.du_group_info.name;
                    f.parentId.value  = data.du_group_info.parent_id;
                }
            }
            else{
                alert("해당 사용자 ID를 찾지 못했습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}
function go_user_group_update2(id, job) {
   
    if (job == "user-group") {
        var f = document.userGroupForm;
        if (f.groupName.value == "") {
            alert("그룹 이름을 입력해주세요."); f.groupName.focus();
            return;
        }else if (f.parentId.value == "") {
            alert("부모 그룹을 선택해주세요."); f.parentId.focus();
            return;
        }
        var du_groupinfo = {"name": f.groupName.value, "parent_id": f.parentId.value};
    }
    $.ajax({
        type : "PUT",
        url : ip + "/v1/user-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        data : JSON.stringify(du_groupinfo),
        success : function(data) {
            if (data.error_code == 1) {
                alert("단말기 그룹을 수정하였습니다.");
                refresh('users');
            }
            else{
                alert("단말기 그룹이 중복되었습니다.");
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
        }
    });
}

function user_group_delete2(job,id) {
    if (job == '1') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/user-groups/" + id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자 그룹을 삭제하였습니다.");
                    refresh('users');
                    
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        var j = 0;
        for (var i = 0; i < choice.length; i++) {
            $.ajax({
                type : "DELETE",
                url : ip + "/v1/user-groups/" + choice[i],
                dataType : 'json',
                contentType : 'application/json',
                async : false,
                success : function(data) {
                    if (data.error_code == 1) {
                        j++;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
            });
        }
        if (i == j) {
            alert("선택된 사용자 그룹을 모두 삭제하였습니다.");
            parent.refresh('user-groups');
            window.close();
        }
        else if (j > 0) {
            alert("선택된 사용자 그룹을 일부만 삭제하였습니다.");
            parent.refresh('user-groups');
            window.close();
        }
        else {
            alert("선택된 사용자 그룹을 삭제하지 못했습니다.");
            parent.refresh('user-groups');
            window.close();
        }
    }
}

function user_delete(id,job) {
    if (confirm("사용자를 삭제하시겠습니까?")){
    if (job == 'users') {
        $.ajax({
            type : "DELETE",
            url : ip + "/v1/users/" + id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if (data.error_code == 1) {
                    alert("해당 사용자를 삭제하였습니다.");
                    refresh('users');
                }
                else{
                    alert("삭제하지 못했습니다.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log("error");
            }
        });
    }else{
        alert("error");
    }
 }else{
    return false;
}
}



function accgroup_device_list(){
  
    
    $(".table_app2").empty();

    var f = document.user_form;
    var id = f.access_id.value;
    var str ='';
    var flag = true;
    
    $.ajax({
        type : "GET",
        url : ip + "/v1/access-groups/" + id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            if (data.error_code == 1) {

                var devices = data.access_group_info.device_ids;
               
                
                if(devices == ""){
                    
                    str += '<div style="font-size:12px;margin-top:1%;color:red;">해당하는 단말기가 없습니다.</div>';
                    $(document).find(".table_app2").append(str);
                    return false;
                }

                for(i=0; i<devices.length; i++){
                   
                $.ajax({ 
                    type : "GET",
                    url : ip + "/v1/devices/" + devices[i],
                    dataType : 'json',
                    contentType : 'application/json',
                    async : false,
                    success : function(data) {
                if(data.error_code == 1){
                    
                    //console.log(devices[i]);
                    //console.log(data);
                    
                    var str1 ='';
                    var str2 ='';
                    
                    if(flag){
                    str1 += '<table style="margin-top:2%;width:100%" class="table table-hover">';
                    str1 += '<thead>';
                    str1 += '<tr style="background-color:#eeeef1;">';
                    str1 += '<th>이름</th>';
                    str1 += '<th>IP주소</th>';
                    str1 += '<th>시리얼번호</th>';
                    str1 += '</tr>';
                    str1 += '</thead>';
                    str1 += '<tbody id="table_app">';
                    $(document).find(".table_app2").append(str1);
                    flag = false;
                    }
                    
                    
                    str2 += '<tr>';
                    str2 += '<td>'+data.device_info.name+'</td>';
                    str2 += '<td>'+data.device_info.device_net_info.ip_addr+'</td>';
                    str2 += '<td>'+data.device_info.product_info.serial_no+'</td>';
                    str2 += '</tr>';
                    // str += '</tbody>';
                    // str += '</table>';
                    $(document).find("#table_app").append(str2);
                    
                }
                
                else if(data.error_code == 2){
                        var str ='';
                        str += '<div style="margin-top:1%;color:red;">해당하는 단말기가 없습니다.</div>';
                        $(document).find(".table_app2").append(str);
                        return false; 
                    }
                   
                }
            
            });
        }   
    }
    },
    error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log("error");
    }
  });

}

function devices_events(job,rs){

if(job == 'off'){
    // console.log($(".btn_on").length);
    //    alert('off');
   var devices = [8,11,36];
   var events_info = {"run" : 0, "bio_data" : 0,"main": 0,"device_ids" : devices,
                     "reply_info": {"reply_to": "events/events", "reply_method": 'MQTT', "reply_msg": 'event_arming'}};

                     console.log(events_info);
    
    $.ajax({
        type: "POST",
        url : ip + "/v1/events/arming",
        dataType : 'json',
        data: JSON.stringify(events_info),
        contentType : 'application/json',
        async : false,
        success : function(data) {
            if(data.error_code ==1){
            console.log(data);
            //$("body").append('<iframe id="subscribe" src="/onepass/phpMQTT/subscribe2.php" width="0" height="0"></iframe>');
            }        
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }

                
    });
       
    }else if(job == 'on'){
        // console.log($(".btn_on").length);
        // alert('on');
    var devices = [8,11,37];
   
    
   var events_info = {"run" : 1, "bio_data" : 0,"main": 0,"device_ids" : devices,
                     "reply_info": {"reply_to": "events/events", "reply_method": 'MQTT', "reply_msg": 'event_arming'}};

                     console.log(events_info);
    
    $.ajax({
        type: "POST",
        url : ip + "/v1/events/arming",
        dataType : 'json',
        data: JSON.stringify(events_info),
        contentType : 'application/json',
        async : false,
        success : function(data) {
            if(data.error_code ==1){
            console.log(data);
            //$("body").append('<iframe id="subscribe" src="/onepass/phpMQTT/subscribe2.php" width="0" height="0"></iframe>');
            }        
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }

                
    });

    }else if(job == 'scan'){
        
       let data = JSON.parse(rs);
       console.log(data);
    
       var curr_temperature = String(data.event_data.curr_temperature);
    
      
       var table = window.parent.document.getElementById('table_row');
       var rows = table.getElementsByTagName('tr');
       let rows_next = rows.length;
       
       if(curr_temperature.length > 2){
        curr_temperature = String(data.event_data.curr_temperature).substr(0,4)+'°C';
       }else if(curr_temperature.length == 1){
            curr_temperature = '';
       }else{
        curr_temperature = String(data.event_data.curr_temperature)+'°C';
       }

       if(data.event_data.user_id == 0){
        data.event_data.user_id = '';
       }
       
        var str = '<tr><td class="py-0"><span class="eve_num"></span></td>';
        str += '<td class="py-0">'+data.event_data.date+ "&nbsp"+data.event_data.time+'</td>';
        str += '<td class="py-0">'+event_main[data.event_data.event_main]+'</td>';
        str += '<td class="py-0">'+event_str[data.event_data.event_main][data.event_data.event_sub]+'</td>';
        str += '<td class="py-0 "><span class="group_name'+rows_next+'"></span></td>';
        str += '<td class="py-0">'+data.event_data.user_id+'</td>';
        str += '<td class="py-0 "><span class="user_name'+rows_next+'" style=""></span></td>';
        str += '<td class="py-0"><span class="job_position'+rows_next+'"></span></td>';
        str += '<td class="py-0"><span class="user_cardno'+rows_next+'"></span></td>';
        str += '<td class="py-0"><span class="temperature'+rows_next+'" style="">'+curr_temperature+'</span></td>';
        str += '<td class="py-0"><span class="mask'+rows_next+'" style=""></span></td>';
        str += '<td class="py-0"><span class="device_name'+rows_next+'" style=""></span></td>';
        str += '<td class="py-0">'+data.event_data.device_serial_no+'</td>';
        str += '<td class="py-0"><span class="user_pic'+rows_next+'" style="" data-toggle="modal" data-target="#user_picture" onClick="javascript:new_src('+rows_next+',\''+data.event_data.pic_url+'\');"></span></td></tr>';
     
        $(parent.document).find("#live_list").append(str);
       
        if(data.event_data.temperature_status == 1){
            $(parent.document).find(`temperature${rows_next}`).attr('style','');
        }
        else if(data.event_data.temperature_status == 2){
            $(parent.document).find(`temperature${rows_next}`).attr('style','color:red;');
        }
        else if(data.event_data.temperature_status == 3){
            $(parent.document).find(`temperature${rows_next}`).attr('style','color:blue;');
        }

        if(data.event_data.pic_url != ""){
            $(parent.document).find(`.user_pic${rows_next}`).addClass('gd-user');
            $(parent.document).find(`.user_pic${rows_next}`).attr('style','cursor:pointer');
           // $(parent.document).find(".user_img").addClass(`user_img${rows_next}`);
            
        }

        for(var i = 0; i < rows.length; i++){
        if(data.event_data.user_id == 0 && event_str[5][76]){
            $(parent.document).find(`.user_name${rows_next}`).attr('style','color:red;');
            $(parent.document).find(`.user_name${rows_next}`).text("미등록자");
            
        }else if(data.event_data.user_id == 0 && event_str[5][112]){
            $(parent.document).find(`.user_name${rows_next}`).attr('style','color:red;');
            $(parent.document).find(`.user_name${rows_next}`).text("미등록자");

        }
        else{
            $(parent.document).find(`.user_name${rows_next}`).attr('style','');
        }

       if(data.event_data.user_id == 0 && event_str[5][22]){
            $(parent.document).find(`.user_name${rows_next}`).text("");
       }else if(data.event_data.user_id == 0 && event_str[5][21]){
           $(parent.document).find(`.user_name${rows_next}`).text("");
       }

        if(data.event_data.user_id != 0 && data.event_data.is_wear_mask == 1){
            $(parent.document).find(`.mask${rows_next}`).attr('style','');
            $(parent.document).find(`.mask${rows_next}`).text("착용");
            
        }else if(data.event_data.user_id != 0 && data.event_data.is_wear_mask == 0){
            $(parent.document).find(`.mask${rows_next}`).attr('style','color:red');
            $(parent.document).find(`.mask${rows_next}`).text("미착용");
        }
        
        $.ajax({
            type: "GET",
            url : ip + "/v1/devices/" + data.event_data.device_id,
            dataType : 'json',
            contentType : 'application/json',
            async : false,
            success : function(data) {
                if(data.error_code ==1){
                console.log(data);
                $(parent.document).find(`.device_name${rows_next}`).text(data.device_info.name);
               
                }        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log("error");
                    }
        });
    
  if(data.event_data.user_id != ''){
    $.ajax({
        type: "GET",
        url : ip + "/v1/users/" + data.event_data.user_id,
        dataType : 'json',
        contentType : 'application/json',
        async : false,
        success : function(data) {
            if(data.error_code == 1){
            //console.log(data);
            $(parent.document).find(`.user_name${rows_next}`).text(data.du_info.name);
            $(parent.document).find(`.group_name${rows_next}`).text(data.du_info.du_group.group_name);
            $(parent.document).find(`.job_position${rows_next}`).text(data.du_info.du_job_position.name);
            //$(parent.document).find(`.user_cardno${rows_next}`).text(data.du_info.secure_info.cardno_list);
                
            
           
            }        
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log("error");
                }
    });
  }
   
    for(var i = 0; i < rows.length; i++){
        $(parent.document).find(".eve_num").eq(i).text(i+1);
    }
    
    //$(parent.document).find("body").append('<iframe id="subscribe" src="/onepass/phpMQTT/subscribe2.php" width="0" height="0"></iframe>');
    }
}
}
function events_scan(rs){
    rs = String(rs).replace('<?="','');
    rs = String(rs).replace('"?>','');
    //console.log(rs);
    devices_events('scan',rs);
}

function new_src(rows,url){
    $(document).find('.user_img').attr('src','');
    $(document).find('.user_img').attr('src',`${url}`);
}

function event_onoff(){

    if($('.btn_on').length == 0){
        devices_events('on');
    }else if($('.btn_on').length > 0){
        devices_events('off');
    }
}