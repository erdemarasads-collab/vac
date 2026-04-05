<footer>
    <div class="container">
        <div class="copyright">© Copyright 2021 | Tüm hakları saklıdır.</div>
        <div class="footer-menu">
            <ul>
                <li class="important-informations">
                    <a href="javascript:void(0);">Önemli Bilgiler</a>
                    <ul>
                        <li><a href="#" data-toggle="modal" data-target="#acik-riza-metni-modal">Açık Rıza Metni</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#aydinlatma-metni-modal">Aydınlatma Metni</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#imha-politikasi-modal">İmha Politikası</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#privacy-policy-footer-modal">Gizlilik Politikası</a></li>
                    </ul>
                </li>
                <li><a href="#" data-toggle="modal" data-target="#comment-modal">Görüş Bildir</a></li>
                <li><a href="#" data-toggle="modal" data-target="#sss-modal">Sıkça Sorulan Sorular</a></li>
                <li><a href="#" data-toggle="modal" data-target="#contact-modal">İletişim</a></li>
            </ul>
        </div>
        <div class="cookie-warning display-none">
            <h3>Çerez Politikası
                <i class="icon close cookie-warning-close-btn" onclick="app.closeCookieWarning();">x</i>
            </h3>
            <p>
                hgs.pttavm.com'da bulunan çerezler (cookies); alışveriş deneyiminizi iyileştirmek için
                yasal mevzuata uygun olarak düzenlenmiştir. Detaylı bilgiye ulaşmak için<br />
                <a href="#" data-toggle="modal" data-target="#cerez-politikasi-modal">Çerez Politikası</a> sayfasını ziyaret edebilirsiniz.
            </p>
        </div>
    </div>
</footer>

</div> <!--image-container-->


<!-- MAINTENANCE MODE -->
<input type="hidden" id="maintenance_hgs" value="0"/>
<input type="hidden" id="maintenance_hasar" value="0"/>
<input type="hidden" id="maintenance_km" value="0"/>
<input type="hidden" id="maintenance_mtv" value="1"/>
<input type="hidden" id="maintenance_trafik" value="1"/>

    <script type="text/javascript"
            src="hgs.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        app.documentReady();
        $(window).trigger('resize')
    });
    window.addEventListener('DOMContentLoaded', () => {
        app.domContentLoaded();
    });
    $(window).resize(function() {
        app.navResizeCheck();
    });
</script>

</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        hgs.documentReady();
    });
</script>
