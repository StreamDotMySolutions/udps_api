import React, { useEffect, useState } from 'react';
import { Badge, Button, Col, Row } from 'react-bootstrap';
import { InputText, InputTextarea, appendFormData } from '../../../../libs/FormInput';
import useStore from '../../../../store';
import axios from 'axios';

const ChatroomComponent = () => {
    const store = useStore()
    const [isLoading, setIsLoading] = useState(false)
    const [isSending, setIsSending] = useState(false)
    const [lockUsername, setLockUsername] = useState(false)
    const [chats, setChats] = useState([])
    const url = process.env.REACT_APP_API_URL; 
    
    // preset 
    useEffect( () => {
        store.setValue('refresh', false)
    },[])    

    // autoscroll to bottom
    useEffect(() => {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    }, [chats]);
    

    // get chats
    useEffect( () => {

        // Function to fetch chats
        const fetchChats = () => {
            // Simulate an API call to get chats (replace this with actual API call)
            console.log('Fetching chats...');
            axios({ 
                method: 'get', 
                url: `${url}/homepage/chats`,
              })
              .then( response => { // success 200
                //console.log(response)
                setChats(response.data.chats)
                store.setValue('refresh', false)
              })
              .catch( error => {
                console.warn(error)
              })
              .finally( () => {
                setIsLoading(false)
              
              })
              setIsSending(false)
            
        };

        // Set an interval to fetch chats every 5 seconds (5000ms)
        const intervalId = setInterval(() => {
            fetchChats();
        }, 2000);

        // Clean up the interval when the component unmounts or effect re-runs
        return () => clearInterval(intervalId);

    },[store.getValue('refresh')])

    const renderChats= () => {
        
        if (!chats || chats.length === 0) {
            return <div></div>;
        } 

        if (chats.length > 0) {
            return chats.map((chat, index) => {
      
                return (
                    <Row key={index}>
                        <Col xs={1}><Badge>{chat?.username}</Badge></Col>
                        <Col className="mt-4" xs={11}><i><small>{chat?.message}</small></i></Col>
                    </Row>
                )
            }) // map
        } // if
    } 

    const appendChat = () => {
        
        
            if(isSending){
                    return (
        
                        
                        <Row>
                            <Col xs={1}>{store.getValue('username')}</Col>
                            <Col xs={11}>{store.getValue('message')}</Col>
                        </Row>
                    
                    )
            }
           
    
        
    }

    const handleSendButton = () => {
        setIsSending(true)
        setLockUsername(true)
        setIsLoading(true)
        console.log('send')
        const formData = new FormData();
        const dataArray = [
          { key: 'username', value: store.getValue('username') },
          { key: 'message', value: store.getValue('message') }, 
          { key: '_method', value: 'post' }, 
        ];
        appendFormData(formData, dataArray); // data appended to formData

        // send to Laravel
        axios({ 
            method: 'post', 
            url: `${url}/homepage/chats`,
            data: formData
          })
          .then( response => { // success 200
            //console.log(response)
            store.setValue('refresh', true) // to force useEffect get new data for index
            store.setValue('message', null) // clear the message  
          })
          .catch( error => {
            console.warn(error)
          })
          .finally( () => {
            setIsLoading(false)
          })
    }

    return (
        <div className="text-dark p-4 rounded" style={{'backgroundColor':'#eaeaea','width':'100%'}}>
            <h2>Chatroom</h2>
            <div 
                id="chat-box" 
                style={{ 
                    'height': '500px', 
                    'overflowY': 'auto', 
                    'overflowX': 'hidden', 
                    'padding': '10px', 
                    'backgroundColor': isSending ? '#999' : '#fff'  // Change background based on isSending state
                }}
            >
                { renderChats() }

                {/* { appendChat() } */}
            </div>

            <Row className='mt-1 p-2'>
                <InputText 
                     fieldName={'username'}
                     icon={'fa-solid fa-user'}
                     placeholder={'Your name'}
                     isLoading={lockUsername }
                />
            </Row>
            
            {store.getValue('username') !== null &&
            (<>
            <Row className='mt-0 ps-2 pe-2'>
 
                <InputTextarea 
                     fieldName={'message'}
                     icon={'fa-solid fa-message'}
                     placeholder={'message'}
                     rows={3}
                     isLoading={isLoading}
                />

                
            </Row>

            <Row className='mt-1 p-2'>
                <Button
                disabled={store.getValue('message') == null}
                onClick={ () => handleSendButton() }
                >Send
                </Button>
            </Row>
            </>)}
        </div>
    );
};
export default ChatroomComponent;
