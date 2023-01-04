<?php

namespace Aponahmed\Uttaragedget\src;

/**
 * Description of Views
 *
 * @author Mahabub
 */
class Views {

    //put your code here
    public static function adminMainView() {
        
        ?>
        <div class="utatra-gadget">
            <div class="overview">
                <div class="box">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Sync</title><path d="M434.67 285.59v-29.8c0-98.73-80.24-178.79-179.2-178.79a179 179 0 00-140.14 67.36m-38.53 82v29.8C76.8 355 157 435 256 435a180.45 180.45 0 00140-66.92" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M32 256l44-44 46 44M480 256l-44 44-46-44"/></svg></div>
                    <div class="box-title">
                        <label class="counter">18</label>
                        <label>Exchange</label>
                    </div>                    
                </div>

                <div class="box" style="background:#ff76b7">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Cube</title><path d="M448 341.37V170.61A32 32 0 00432.11 143l-152-88.46a47.94 47.94 0 00-48.24 0L79.89 143A32 32 0 0064 170.61v170.76A32 32 0 0079.89 369l152 88.46a48 48 0 0048.24 0l152-88.46A32 32 0 00448 341.37z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M69 153.99l187 110 187-110M256 463.99v-200"/></svg>
                    </div>
                    <div class="box-title">
                        <label class="counter">190</label>
                        <label>Products</label>
                    </div>                    
                </div>

                <div class="box" style="background:#7698ff">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>People</title><path d="M402 168c-2.93 40.67-33.1 72-66 72s-63.12-31.32-66-72c-3-42.31 26.37-72 66-72s69 30.46 66 72z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M336 304c-65.17 0-127.84 32.37-143.54 95.41-2.08 8.34 3.15 16.59 11.72 16.59h263.65c8.57 0 13.77-8.25 11.72-16.59C463.85 335.36 401.18 304 336 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path d="M200 185.94c-2.34 32.48-26.72 58.06-53 58.06s-50.7-25.57-53-58.06C91.61 152.15 115.34 128 147 128s55.39 24.77 53 57.94z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M206 306c-18.05-8.27-37.93-11.45-59-11.45-52 0-102.1 25.85-114.65 76.2-1.65 6.66 2.53 13.25 9.37 13.25H154" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/></svg> 
                    </div>
                    <div class="box-title">
                        <label class="counter">88</label>
                        <label>Customers</label>
                    </div>                    
                </div>

                <div class="box" style="background:#0bb756">
                    <div class="box-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Receipt</title><path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" d="M160 336V48l32 16 32-16 31.94 16 32.37-16L320 64l31.79-16 31.93 16L416 48l32.01 16L480 48v224"/><path d="M480 272v112a80 80 0 01-80 80h0a80 80 0 01-80-80v-48H48a15.86 15.86 0 00-16 16c0 64 6.74 112 80 112h288" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M224 144h192M288 224h128"/></svg>
                    </div>
                    <div class="box-title">
                        <label class="counter">54</label>
                        <label>Invoice</label>
                    </div>                    
                </div>
            </div>
            <div class="uttg-exchange-zone">

            </div>
        </div>
        <?php
    }

}
