import  { useEffect, useState } from 'react'
import axios from 'axios'
import { Row,Col } from 'react-bootstrap';

import VideoComponent from './components/VideoComponent';
import SongComponent from './components/SongComponent';
import DeejayComponent from './components/DeejayComponent';
import BannerComponent from './components/BannerComponent';
import ChatroomComponent from './components/ChatroomComponent';
import VoteComponent from './components/TopicComponent';

import './style.css';

const Home = () => {

    const url = process.env.REACT_APP_API_URL;
    const serverUrl = process.env.REACT_APP_SERVER_URL;
    const [modules,setModules] = useState([]);
    const [isLoading,setIsLoading] = useState(false);

    useEffect( () => {
        setIsLoading(true)
        axios(`${url}/homepage/modules`)
        .then( response => {
          console.log(response)
          setModules(response.data.modules)
        })
        .catch( error => {
          console.warn(error)
        })
        .finally( () =>{ 
          setIsLoading(false)
        })
      },[]);

    const items = () => {

        if (modules.length > 0) {
            return modules.map((module, index) => {
      
                return (<>
                    {module.name}
                </>)
            })
        }
    }
    

    return (
        <>
      
            <Row className="mt-4 mb-4 ms-1 ">
                <BannerComponent />
            </Row>

            <Row className="mt-4 mb-4 ms-1 pt-4 pb-3 rounded ">
                {/* <CardComponent /> */}
                <DeejayComponent />
            </Row>
            
            <Row className="mt-4 mb-4 ms-1  pt-4 pb-3  rounded text-light videocomponentbg">
                <Col  xs={12} md={8} className=" p-3 rounded">
                    <VideoComponent />
                </Col>

                <Col xs={12} md={4} className=" p-3 rounded ">
                    <ChatroomComponent />
                </Col>
            </Row>

            {/* <Row className="justify-content-center mt-4 mb-4 ms-1 pt-4 pb-3">
                <VoteComponent />
            </Row> */}

            <Row className="justify-content-center mt-4 mb-4 ms-1 pt-4 pb-3">
                 <SongComponent /> 
            </Row>

        </>
    );
};

export default Home;