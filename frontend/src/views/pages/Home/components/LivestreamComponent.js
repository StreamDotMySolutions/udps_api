import React from 'react';
import {Col,Row } from 'react-bootstrap'
import VideoComponent from './VideoComponent';
import ChatroomComponent from './ChatroomComponent';


const LivestreamComponent = () => {
    return (
            <Row className="mt-4 mb-4 ms-1  pt-4 pb-3  rounded text-light videocomponentbg">
                <Col  xs={12} md={8} className=" p-3 rounded">
                    <VideoComponent />
                </Col>

                <Col xs={12} md={4} className=" p-3 rounded ">
                    <ChatroomComponent />
                </Col>
            </Row>
    );
};

export default LivestreamComponent;