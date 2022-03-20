<div class="wrap wps-admin-wrap">

   <style>
      @keyframes shimmer {
         0% {
            opacity: 0.6;
         }

         100% {
            opacity: 1;
         }
      }
   </style>
   
   <div id="shopwp-skeleton-loader" style="position: absolute;top: 20px;left: 50%;transform: translate(-50%, 0);width: 100%;margin: -21px 0 0 0;">

      <header class="wps-skeleton-header" style="width: 100%;height:150px;background: rgb(255, 255, 255);box-sizing: border-box;border-radius: 3px;border: 1px solid rgb(226, 228, 231);"></header>

      <svg width="570" height="460" aria-labelledby="loading-aria" preserveAspectRatio="none" style="animation: shimmer 0.3s ease-out 0s alternate infinite none running;margin:-154px auto 0;" display="block"><rect width="100%" height="100%" clip-path="url(#clip-path)" fill="url(&quot;#fill&quot;)"/><defs><linearGradient id="fill"><stop offset=".6" stop-color="#e3e3e3"><animate attributeName="offset" values="-2; -2; 1" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop><stop offset="1.6" stop-color="#e3e3e3"><animate attributeName="offset" values="-1; -1; 2" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop><stop offset="2.6" stop-color="#e3e3e3"><animate attributeName="offset" values="0; 0; 3" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop></linearGradient><clipPath id="clip-path"><rect rx="0" ry="0"/><rect x="8" y="168" rx="2" ry="2" width="600" height="326"/><rect x="18" y="122" rx="2" ry="2" width="94" height="16"/><rect x="198" y="41" rx="2" ry="2" width="262" height="41"/><circle cx="153" cy="62" r="32"/><rect x="130" y="122" rx="2" ry="2" width="94" height="16"/><rect x="243" y="122" rx="2" ry="2" width="94" height="16"/><rect x="358" y="122" rx="2" ry="2" width="94" height="16"/><rect x="475" y="123" rx="2" ry="2" width="87" height="15"/></clipPath></defs></svg>

   </div>

   <div id="shopwp-admin-app"></div>
   <div id="shopwp-admin-content"></div>
   <div id="shopwp-admin-footer"></div>

</div>