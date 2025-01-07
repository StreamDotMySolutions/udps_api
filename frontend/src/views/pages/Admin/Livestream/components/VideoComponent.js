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
       <>
        {isLive ? (
            <>
            <ReactPlayer
                onProgress={(progress) => {
                    setPlayed(progress.playedSeconds);
                }}
                url={src}
                playing={true}
                controls={true}
                // volume={0}
                // muted={true}
                width="400px"
                height="auto"
            />
          </>
        ) : (
          <>
            <h2 className='text-center'>Offline</h2>
          </>
        )}
    </>
      
    );
};

export default VideoComponent;