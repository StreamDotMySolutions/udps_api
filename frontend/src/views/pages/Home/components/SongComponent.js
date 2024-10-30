import React, { useEffect,useState } from 'react';
import { Row,Col, FigureImage } from 'react-bootstrap';
import axios from 'axios'
import alan_gambar from './alan.jpg';
import lagu_alan from './onmyway.mp3';
import './lagu.css';
import VoteModal from './ChoiceComponent/VoteModal';

const SongComponent = () => {
  const url = process.env.REACT_APP_API_URL;
  const serverUrl = process.env.REACT_APP_SERVER_URL;
  const [topic,setTopic] = useState([]);
  const [choices,setChoices] = useState([]);
  const [isLoading,setIsLoading] = useState(false);
  const votedTopicId = localStorage.getItem('topic_id'); // to check if user already voted

  //console.log(votedTopicId)

  useEffect( () => {
      setIsLoading(true)
      axios(`${url}/homepage/topics`)
      .then( response => {
        console.log(response)
        setTopic(response.data.topic)
        setChoices(topic?.choices)
      })
      .catch( error => {
        console.warn(error)
      })
      .finally( () =>{ 
        setIsLoading(false)
      })
    },[])

 
  return (
    <Row className="justify-content-center mt-4 mb-4 ms-1 pt-4 pb-3">
    <div className="container-fluid p-5">

    <div className="row">
      <h2><strong>{topic?.title}</strong></h2>
      <span className='text-muted'>
        {topic?.description}
      </span>
      <br /><br />
      {topic.choices?.map((item, index) => (
        <>
        {/* Kad Lagu 1 */}
      <div className="col-md-6 col-lg-4 col-xl-3 mb-3 mt-5">
        <div className="music-carta-lagu">
          <nav>
            <a id="musikplayerundifont">
              <div className="circle text-dark">
             
              { votedTopicId != topic.id  ?
               <VoteModal topicId={topic.id} choiceId={item.id} />
              :
              <p className='text-white'>Voted</p> 
              }
              </div>
            </a>
          </nav>

          {/* <img src={alan_gambar} className="song-img" alt="Alan Walker" /> */}
          <FigureImage
                    className="song-img"
                    src={`${serverUrl}/storage/choices/${item.filename}`} 
                    alt={item.title}
                  />
          <h3><strong>{item.title}</strong></h3>
          <p>{item.description}</p>

          {item.songfile && 
            <audio controls>
              <source  src={`${serverUrl}/storage/songfiles/${item.songfile}`} type="audio/mpeg" />
            </audio>
          } {/* jika ada lagu upload */} 
        </div>
        <br />
      </div>
      {/* Kad Lagu 1 */}
        </>
      ))}
      
      
    </div>
  </div>
  </Row>
  );
}

export default SongComponent;
