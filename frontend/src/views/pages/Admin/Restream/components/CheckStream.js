import React, { useState, useEffect } from 'react';
import { Badge, Col, Row } from 'react-bootstrap';
import ReactPlayer from 'react-player';

const CheckStream = () => {

    //const src =`https://nasionalfm.rtm.gov.my/hls/myStream.m3u8`
    const src = process.env.REACT_APP_STREAMING_URL;
    const [isLive, setIsLive] = useState(false);


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
    <h2>
    Incoming Livestream:{" "}
    <Badge bg={isLive ? "success" : "danger"}>
        {isLive ? "Online" : "Offline"}
    </Badge>
    </h2>
    </>
      
    );
};

export default CheckStream;