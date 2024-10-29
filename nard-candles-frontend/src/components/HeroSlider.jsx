import  { useState, useEffect } from 'react';
import axios from 'axios';

const HeroSlider = () => {
    const [promotions, setPromotions] = useState([]);

    useEffect(() => {
        axios.get('http://localhost:8000/api/promotions')
            .then(response => {
                setPromotions(response.data);
            })
            .catch(error => {
                console.error('Error fetching promotions:', error);
            });
    }, []);

    return (
        <div className="hero-slider">
            {promotions.length > 0 ? (
                <div className="slider">
                    {promotions.map((promo, index) => (
                        <div key={index} className="slide">
                            <img src={`/images/${promo.image}`} alt={promo.title} />
                            <div className="slide-content">
                                <h2>{promo.title}</h2>
                                <p>{promo.description}</p>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <p>Loading promotions...</p>
            )}
        </div>
    );
};

export default HeroSlider;
