// GuestLayout.js
import { Outlet } from 'react-router-dom';
import Footer from './Footer';
import Logo from './nasional-fm.png';
import Podcast from './rtm-podcast-icon.png';
import { useEffect } from 'react';
import './style.css';

const HomeLayout = () => {


  useEffect(() => {
    // Apply background color to the body element
    document.body.style.backgroundColor = '#eeeeee';

    // Cleanup when the component unmounts (optional)
    return () => {
      document.body.style.backgroundColor = '';
    };
  }, []);

  useEffect(() => {
    // Create link elements
    const link1 = document.createElement('link');
    link1.rel = 'preconnect';
    link1.href = 'https://fonts.googleapis.com';
    
    const link2 = document.createElement('link');
    link2.rel = 'preconnect';
    link2.href = 'https://fonts.gstatic.com';
    link2.crossOrigin = 'true';
    
    const link3 = document.createElement('link');
    link3.href = 'https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap';
    link3.rel = 'stylesheet';

    // Append links to the head of the document
    document.head.appendChild(link1);
    document.head.appendChild(link2);
    document.head.appendChild(link3);

    return () => {
      // Cleanup: remove the links when the component unmounts
      document.head.removeChild(link1);
      document.head.removeChild(link2);
      document.head.removeChild(link3);
    };
  }, []);


  return (
    <>
    
      {/* Header */}
      <div className="hero-image ">
        <div  className="d-flex flex-row  opacity-75 p-2 gap-4"></div>
      </div>

      <div className="container-fluid row overflow-auto colorcontainerkelabu">
        <div className="col d-flex flex-column h-sm-100 mb-2 mt-4">
            <Outlet />
            <Footer />
        </div>
    </div>
</>
  );
};

export default HomeLayout;
