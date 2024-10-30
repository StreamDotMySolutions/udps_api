import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Row, Col } from 'react-bootstrap';


import SongComponent from './components/SongComponent';
import DeejayComponent from './components/DeejayComponent';
import BannerComponent from './components/BannerComponent';
import ChatroomComponent from './components/ChatroomComponent';
import LivestreamComponent from './components/LivestreamComponent';

import './style.css';

const Module = () => {
    const url = process.env.REACT_APP_API_URL;
    const [modules, setModules] = useState([]);
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        setIsLoading(true);
        axios.get(`${url}/homepage/modules`)
            .then(response => {
                setModules(response.data.modules);
            })
            .catch(error => {
                console.warn(error);
            })
            .finally(() => { 
                setIsLoading(false);
            });
    }, []);

    // Component mapping for dynamic rendering
    const componentMapping = {
        livestream: LivestreamComponent,
        vote: SongComponent,
        deejay: DeejayComponent,
        banner: BannerComponent,
        chatroom: ChatroomComponent,
    };

    // Function to render components based on module name
    const renderItems = () => {
        return modules.map((module, index) => {
            const Component = componentMapping[module.name];
            return Component ? (
                <Row className="mt-4 mb-4 ms-1" key={index}>
                    <Component />
                </Row>
            ) : (
                <div key={index}>Component not found for {module.name}</div>
            );
        });
    };

    return (
        <div>
            {isLoading ? <p>Loading...</p> : renderItems()}
        </div>
    );
};

export default Module;
