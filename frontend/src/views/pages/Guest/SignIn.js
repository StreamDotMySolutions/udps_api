import { useState, useEffect } from 'react';
import { Row,Alert, Form } from 'react-bootstrap';
import axios from 'axios';
import { appendFormData, InputText } from '../../../libs/FormInput';
import { Navigate } from 'react-router-dom'
import SubmitButton from '../../../libs/SubmitButton';
import useStore from '../../../store';
import AuthStore from '../../../stores/AuthStore'

function SignIn() {
    const store = useStore(); // zustand store management
    const url = process.env.REACT_APP_API_URL; // API server
    const [isLoading, setIsLoading] = useState(false);
    const [message, setMessage] = useState('');
    const [messageType, setMessageType] = useState('');
   
    useEffect(() => {
        // Code to run when the component is loaded (similar to window.onload)
        console.log("Page has loaded!");

        // Zustand presets
        store.emptyData() // clear all previous data
        store.setValue('authenticated', false) // set authenticated to false

        // Optionally, you can return a cleanup function to run when the component is unmounted
        return () => {
            console.log("Component is unmounting!");
        };
    }, []); // Empty dependency array means this runs only once, when the component loads


    // handle form submission
    const handleSubmit = (e) => {
        store.setValue('errors', null ) // reset the error when form is submitting
        setIsLoading(true)
        e.preventDefault();
        
        const formData = new FormData();
        const dataArray = [
            { key: 'email', value: store.getValue('email') }, 
            { key: 'password', value: store.getValue('password') }, 
        ];
        
        appendFormData(formData, dataArray);
        axios({
            method: 'post',
            url: `${url}/login`,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(response => {
            //console.log(response);
            localStorage.setItem('token', response.data.token) // token to be used with axios interceptor
            localStorage.setItem('role', response.data.role) // token to be used with profile
            store.setValue('authenticated', true) // for redirect purpose
            console.log('Form submitted successfully!');
        })
        .catch(error => {
            if( error.response?.status == 422 ){ // detect 422 errors by Laravel
                console.log(error.response.data.errors)
                store.setValue('errors', error.response.data.errors ) // set the errors to store
            }

            if(error?.message){
                setMessageType('danger')
                setMessage(error.message)
            }
            console.warn(error.message)
        })
        .finally( () => {
            setIsLoading(false)
        })
    };

    // handle redirect after login
    if (store.getValue('authenticated') === true) {
        const role = localStorage.getItem('role'); // Get the user role

        // Switch based on the role
        switch (role) {
            case 'system':
                return <Navigate to='/system/home' replace />; // Redirect admin to /system
            case 'admin':
                return <Navigate to='/admin/home' replace />; // Redirect admin to /admin
            case 'user':
                return <Navigate to='/user/home' replace />; // Redirect user to /dashboard
            default:
                return <Navigate to='/' replace />; // Default redirect (e.g., home or error)
        }
    }


    return (
        <Row className='ms-4 col-8 border border-1 p-4 rounded'  style={{ backgroundColor: isLoading ? '#eaeaea' : 'transparent' }} >
        <h1>Sign In</h1>

     
        <Form onSubmit={handleSubmit}>

            <Row>
                {message && <Alert variant={messageType}>{message}</Alert>}
                <Row className='mb-4'>
                    <InputText 
                        type='text'
                        fieldName='email' 
                        placeholder='Your email address'  
                        icon='bi-envelope'
                        isLoading={isLoading}
                    />
                </Row>

                <Row className='mb-4'>
                    <InputText 
                        type='password'
                        fieldName='password' 
                        placeholder='Password'  
                        icon='bi-lock'
                        isLoading={isLoading}
                    />
                </Row>

              
            </Row>

            <SubmitButton isLoading={isLoading} value="Sign In" />
        </Form>
        </Row>
    );
}

export default SignIn;
