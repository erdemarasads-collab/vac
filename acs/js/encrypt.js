String.prototype.padLeft = function(n, pad) {
    t = '';
    if (n > this.length) {
        for (i = 0; i < n - this.length; i++) {
            t += pad;
        }
    }
    return t + this;
};

String.prototype.padRight = function(n, pad) {
    t = this;
    if (n > this.length) {
        for (i = 0; i < n - this.length; i++) {
            t += pad;
        }
    }
    return t;
};

/**
 * requires <script language="JavaScript" type="text/javascript"
 * src="js/base64.js"></script>
 * 
 * @param kartTuru
 * @param exponentBase64
 * @param modulusBase64
 * @param challange
 * @param sifre3d
 * @returns
 */
function encryptPasswordBase64(kartTuru, exponentBase64, modulusBase64,
    challange, sifre3d) {

    var convertedSifre = TTE(sifre3d);

    //var toEncText = challange + sifre3d;
    var toEncText = challange + convertedSifre;
    if (kartTuru == 'T') // 6 byte challenge + 6 byte password
    {
        toEncText = toEncText.padRight(15, ' ');
    } else // 6 byte challenge + 9 byte password
    {
        toEncText = toEncText.padRight(12, ' ');
    }
    var pkModulusHex = b64tohex(modulusBase64);
    var pkExponentHex = b64tohex(exponentBase64);
    var hsmrsa = new HsmRsa();
    var valHex = hsmrsa
        .rsaPublicEncrypt(pkExponentHex, pkModulusHex, toEncText);
    var val = hex2b64(valHex);

    // alert("challenge=" + challange +
    // "\nsifre3d=" + sifre3d +
    // "\ntoEncText=" + toEncText +
    // "\ntoEncTextLen=" + toEncText.length +
    // "\nmodulusBase64=" + modulusBase64 +
    // "\nexponentBase64=" + exponentBase64 +
    // "\nsifreEncrypted=" + val);

    return val;
}

function ticariKayitOnSubmit(kartTuru, challange, sifre3d, sifre3d1, modulus,
    exp) {
    if (kartTuru == 'T') {
        if (sifre3d != sifre3d1) {
            alert("Şifre olarak girdiğiniz değerler birbirinden farklı. Lütfen aynı değerleri giriniz.");
            return false;
        }

        if (sifre3d.length < 6 || sifre3d.length > 9) {
            alert("Belirlediğiniz şifre en az 6, en fazla 9 karakter olabilir");
            return false;
        }
    } else {
        if (sifre3d.length != 6) {
            alert("Belirlediğiniz şifre 6 karakter olmalıdır");
            return false;
        }
    }

    return encryptBothPasswords(kartTuru, challange, sifre3d, sifre3d1,
        modulus, exp);
}

/**
 * Encrypt sifre ve sifre yeniden
 * 
 * @param kartTuru
 */
function encryptBothPasswords(kartTuru, challange, sifre3d, sifre3d1, modulus,
    exp) {

    if ('' == sifre3d || '' == sifre3d1 || sifre3d != sifre3d1) {
        alert(messageResolver["js.message.invalidPsw"]);
        return false;
    }

    var val = encryptPasswordBase64(kartTuru, exp, modulus, challange, sifre3d);

    document.getElementById('sifre3d').value = val;
    document.getElementById('sifre3d1').value = val;
    document.getElementById('psifre3d').value = '';
    document.getElementById('psifre3d1').value = '';

    return true;
}

/** ** Turkce karakter problemi için yeni eklenen fonksiyonlar ******* */


function TTE(strInput) {
    var newStr = "";

    if (strInput.length == 0) {
        return newStr;
    }

    for (i = 0; i < strInput.length; i++) {
        switch (strInput.charCodeAt(i)) {
            case 246:
                newStr += "o";
                break;
            case 214:
                newStr += "O";
                break;
            case 231:
                newStr += "c";
                break;
            case 199:
                newStr += "C";
                break;
            case 351:
                newStr += "s";
                break;
            case 350:
                newStr += "S";
                break;
            case 304:
                newStr += "I";
                break;
            case 287:
                newStr += "g";
                break;
            case 286:
                newStr += "G";
                break;
            case 252:
                newStr += "u";
                break;
            case 220:
                newStr += "U";
                break;
            case 305:
                newStr += "i";
                break;
            default:
                newStr += strInput.charAt(i);
                break;
        }
    }
    return newStr.toUpperCase();
};