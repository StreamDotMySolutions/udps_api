import React, { useEffect } from 'react';

const NginxComponent = () => {
  useEffect(() => {
    const interval = setInterval(() => {
      const iframe = document.getElementById('statusIframe');
      if (iframe) {
        iframe.src = iframe.src; // Refresh the iframe
      }
    }, 2000); // 2 seconds

    // Cleanup interval on component unmount
    return () => clearInterval(interval);
  }, []);

  return (
   
      <div className="ratio ratio-16x9 rounded" style={{ width:'400px' ,height: '100px', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
        <iframe
          id="statusIframe"
          src="https://nasionalfm.rtm.gov.my/nginx_status"
          frameBorder="0"
          title="Nginx Status"
   
        ></iframe>
      </div>

  );
};

export default NginxComponent;
