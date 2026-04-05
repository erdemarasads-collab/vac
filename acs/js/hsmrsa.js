/**
 * RSA public encryption utility requires rsa.js, bigint.js, barrett.js
 * 
 * @see http://www-cs-students.stanford.edu/~tjw/jsbn/rsa.html
 * @see http://code.google.com/p/pajhome/source/checkout
 */
function HsmRsa() {
    /**
     * fonksiyon init kismi
     * 
     * @param exp
     * @param modulus
     */
    this.initKey = function(exp, modulus) {
        setMaxDigits(131);
        return new RSAKeyPair(exp, "", modulus);
    };

    /**
     * public sifreleme icin kullanilir.
     * 
     * @param exp
     *            exponent of public key
     * @param modulus
     *            modulus of public key
     * @param plainText
     *            string to be encrypted
     */
    this.rsaPublicEncrypt = function(exp, modulus, plainText) {
        var key = this.initKey(exp, modulus);
        return encryptedString(key, plainText);
    };

}