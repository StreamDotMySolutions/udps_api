import React, { useState, useEffect } from 'react';
import { Col, Row } from 'react-bootstrap';
import ReactPlayer from 'react-player';

const VideoComponent = () => {
    const [played, setPlayed] = React.useState(0);
    //const src =`https://nasionalfm.rtm.gov.my/hls/myStream.m3u8`
    const src = process.env.REACT_APP_STREAMING_URL;
    const [isLive, setIsLive] = useState(false);
    const [refreshKey, setRefreshKey] = useState(0); // key to force refresh

    useEffect(() => {
        const checkStream = () => {
            console.log('check stream')
            fetch(src, { method: 'HEAD' })
                .then(response => {
                if (response.ok) {
                    setIsLive(true);
                    //setRefreshKey(prevKey => prevKey + 1); // refresh when stream is live
                } else {
                    setIsLive(false);
                }
                })
                .catch(() => {
                setIsLive(false);
                });
        };

    checkStream(); // Check immediately when component mounts
    const intervalId = setInterval(checkStream, 2000); // Check every 2 seconds

    return () => clearInterval(intervalId); // Clean up on component unmount
  }, []);


    return (
       
        <Row key={refreshKey} className="rounded" style={{ height: '100%', backgroundColor: '#000', display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
        {isLive ? (
            <>
            <h2 className='text-danger'><i class="fa-solid fa-camera"></i> LIVE</h2>
            <ReactPlayer
                onProgress={(progress) => {
                    setPlayed(progress.playedSeconds);
                }}
                url={src}
                playing={true}
                controls={true}
                // volume={0}
                // muted={true}
                width="100%"
                height="auto"
            />
          </>
        ) : (
          <>
            <h1 className='text-center' style={{ color: '#fff' }}>Offline</h1>
          </>
        )}
      </Row>
      
    );
};

export default VideoComponent;