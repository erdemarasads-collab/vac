/** *** start backup **** */
function RSAKeyPair(encryptionExponent, decryptionExponent, modulus) {
    this.e = biFromHex(encryptionExponent);
    this.d = biFromHex(decryptionExponent);
    this.m = biFromHex(modulus);
    this.digitSize = 2 * biHighIndex(this.m) + 2;
    this.chunkSize = this.digitSize - 11;
    this.radix = 16;
    this.barrett = new BarrettMu(this.m)
}

function twoDigit(n) {
    return (n < 10 ? "0" : "") + String(n)
}
var unicode = [286, 220, 350, 304, 214, 199, 287, 252, 351, 305, 246, 231];
var win1254 = [208, 220, 222, 221, 214, 199, 240, 252, 254, 253, 246, 231];
var utf8_lo = [196, 195, 197, 196, 195, 195, 196, 195, 197, 196, 195, 195];
var utf8_hi = [158, 156, 158, 176, 150, 135, 159, 188, 159, 177, 182, 167];

function toWin1254(charCode) {
    for (var i = 0; i < unicode.length; i++) {
        if (unicode[i] == charCode) {
            return win1254[i]
        }
    }
    return charCode
}

function toUtf8(charCode) {
    for (var i = 0; i < unicode.length; i++) {
        if (unicode[i] == charCode) {
            return {
                lo: utf8_lo[i],
                hi: utf8_hi[i]
            }
        }
    }
    return charCode > 127 ? 63 : charCode
}

function getRandomInt() {
    var cryptoObj = window.crypto || window.msCrypto; // for IE 11
    if (cryptoObj === undefined || cryptoObj === null) {
        return Math.random();
    }
    var arr = new Uint8Array(1);
    cryptoObj.getRandomValues(arr);
    var result = arr[0] * Math.pow(2, -8);
    return result;
}

function encryptedString(key, s) {
    if (key.chunkSize > key.digitSize - 11) {
        return "Error"
    }
    var a = new Array();
    var utf8Array = new Array();
    var sl = s.length;
    var i = 0;
    while (i < sl) {
        a[i] = s.charCodeAt(i);
        utf8rprs = toUtf8(a[i]);
        if (utf8rprs.lo) {
            utf8Array.push(utf8rprs.lo);
            utf8Array.push(utf8rprs.hi)
        } else {
            utf8Array.push(utf8rprs)
        }
        i++
    }
    a = utf8Array;
    var al = a.length;
    var result = "";
    var j, k, block;
    for (i = 0; i < al; i += key.chunkSize) {
        block = new BigInt();
        j = 0;
        var x;
        var msgLength = (i + key.chunkSize) > al ? al % key.chunkSize :
            key.chunkSize;
        var b = new Array();
        for (x = 0; x < msgLength; x++) {
            b[x] = a[i + msgLength - 1 - x]
        }
        b[msgLength] = 0;
        var paddedSize = Math.max(8, key.digitSize - 3 - msgLength);
        for (x = 0; x < paddedSize; x++) {
            b[msgLength + 1 + x] = Math.floor(getRandomInt() * 254) + 1
        }
        b[key.digitSize - 2] = 2;
        b[key.digitSize - 1] = 0;
        for (k = 0; k < key.digitSize; ++j) {
            block.digits[j] = b[k++];
            block.digits[j] += b[k++] << 8
        }
        var crypt = key.barrett.powMod(block, key.e);
        var text = key.radix == 16 ? biToHex(crypt) : biToString(crypt,
            key.radix);
        result += text + " "
    }
    return result.substring(0, result.length - 1)
}


function decryptedString(key, s) {
    var blocks = s.split(" ");
    var result = "";
    var i, j, block;
    for (i = 0; i < blocks.length; ++i) {
        var bi;
        if (key.radix == 16) {
            bi = biFromHex(blocks[i])
        } else {
            bi = biFromString(blocks[i], key.radix)
        }
        block = key.barrett.powMod(bi, key.d);
        for (j = 0; j <= biHighIndex(block); ++j) {
            result += String.fromCharCode(block.digits[j] & 255,
                block.digits[j] >> 8)
        }
    }
    if (result.charCodeAt(result.length - 1) == 0) {
        result = result.substring(0, result.length - 1)
    }
    return result
};
/** ** end backup **** */