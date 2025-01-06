import React from 'react';
import VideoComponent from './components/VideoComponent';
import NginxComponent from './components/NginxComponent';
import { Table } from 'react-bootstrap';

const index = () => {
    const src = process.env.REACT_APP_STREAMING_URL;
    return (
        <div  style={{'width':'800px'}}>
            <h2>Stream Publishing</h2>
            <Table>
                <tr>
                    <th>Server:</th>
                    <td>rtmp://nasionalfm.rtm.gov.my/publish</td>
                </tr>
                <tr>
                    <th>Stream Key:</th>
                    <td>nasionalfm</td>
                </tr>

                <tr>
                    <th>Playback URL:</th>
                    <td>{src}</td>
                </tr>

                <tr>
                    <th>Player</th>
                    <td><VideoComponent /></td>
                </tr>

                <tr>
                    <th></th>
                    <td style={{'height':'10px'}}></td>
                </tr>

                <tr >
                    <th>Connected Device:</th>
                    <td><NginxComponent /></td>
                </tr>
            </Table>
       

         
    
        </div>
    );
};

export default index;