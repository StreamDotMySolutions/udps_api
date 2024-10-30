import React from 'react';
import { Form } from 'react-bootstrap';
import axios from '../../../../../libs/axios'
import useStore from '../../../../../store'

const StatusComponent = ({id, is_active }) => {
    const store = useStore() // global store
    const url = process.env.REACT_APP_API_URL; 

    const handleStatusChange = (id,is_active) => {
       
        // is_active is current value
        // we need to send the opposite value
        // example if given is 0, then we send 0
        const is_active_value = is_active ? 0 : 1

        // activate / deactivate  based on toggle on is_active
        // send request to laravel
        axios(`${url}/admin/modules/activation/${id}/${is_active_value}`)
        .then( response => {
            //console.log(response)
            store.setValue('refresh', true) // trigger DataTable useEffect()
        })
        .catch( error => {
            console.warn(error)
        })
    }

    return (
        <div>
           
            <Form.Check // prettier-ignore
                
                checked={is_active == 1 ? true : false}
                type="switch"
                id="custom-switch"
                onChange={() => handleStatusChange(id, is_active) }
                //label="Check this switch"
            />
</div>
    );
};

export default StatusComponent;