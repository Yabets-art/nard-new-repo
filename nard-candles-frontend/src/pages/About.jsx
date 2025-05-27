import { useEffect, useRef } from 'react';
import './About.css';

const About = () => {
  const observerRef = useRef(null);

  useEffect(() => {
    observerRef.current = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate');
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach((element) => {
      observerRef.current.observe(element);
    });

    return () => {
      if (observerRef.current) {
        observerRef.current.disconnect();
      }
    };
  }, []);

  return (
    <div className="about-container">
      {/* Hero Section */}
      <section className="about-hero animate-on-scroll fade-down">
        <div className="hero-content">
          <h1>About Nard Candles</h1>
          <div className="hero-description">
            <p>
              Welcome to <strong>Nard Candles</strong>, where luxury meets craftsmanship. 
              We specialize in creating and selling luxury scented candles that are designed 
              to elevate any space with their premium fragrances and elegant, handcrafted designs.
            </p>
          </div>
          <div className="floating-candles">
            <div className="candle-icon">🕯️</div>
            <div className="candle-icon">🕯️</div>
            <div className="candle-icon">🕯️</div>
          </div>
        </div>
      </section>

      {/* Mission Section */}
      <section className="mission-section animate-on-scroll slide-up">
        <div className="section-content">
          <h2>Our Mission</h2>
          <div className="decorative-line"></div>
          <p>
            At Nard Candles, we believe that a well-crafted candle can do more than just provide light; 
            it can enhance your home, create memorable moments, and offer a touch of luxury to everyday life. 
            Our mission is to bring warmth, comfort, and elegance to every room through our meticulously 
            designed candles.
          </p>
          <div className="mission-values">
            <div className="value-card animate-on-scroll fade-in" style={{ animationDelay: '0.2s' }}>
              <span className="value-icon">✨</span>
              <h3>Quality</h3>
              <p>Premium materials and expert craftsmanship</p>
            </div>
            <div className="value-card animate-on-scroll fade-in" style={{ animationDelay: '0.4s' }}>
              <span className="value-icon">🌿</span>
              <h3>Sustainability</h3>
              <p>Eco-friendly practices and materials</p>
            </div>
            <div className="value-card animate-on-scroll fade-in" style={{ animationDelay: '0.6s' }}>
              <span className="value-icon">💝</span>
              <h3>Innovation</h3>
              <p>Continuous improvement and creativity</p>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="features-section animate-on-scroll slide-up">
        <h2>What Sets Us Apart</h2>
        <div className="decorative-line"></div>
        <div className="features-grid">
          <div className="feature-card animate-on-scroll fade-in" style={{ animationDelay: '0.2s' }}>
            <div className="feature-icon">🌺</div>
            <h3>Premium Fragrances</h3>
            <p>Carefully selected high-quality fragrances that fill your space with warmth and character.</p>
            <div className="hover-content">
              <ul>
                <li>Natural essential oils</li>
                <li>Long-lasting scents</li>
                <li>Unique blends</li>
              </ul>
            </div>
          </div>
          <div className="feature-card animate-on-scroll fade-in" style={{ animationDelay: '0.4s' }}>
            <div className="feature-icon">🎨</div>
            <h3>Handcrafted Designs</h3>
            <p>Each candle is handcrafted with precision and care for unique, elegant results.</p>
            <div className="hover-content">
              <ul>
                <li>Artistic patterns</li>
                <li>Custom designs</li>
                <li>Elegant finishing</li>
              </ul>
            </div>
          </div>
          <div className="feature-card animate-on-scroll fade-in" style={{ animationDelay: '0.6s' }}>
            <div className="feature-icon">🌱</div>
            <h3>Natural Ingredients</h3>
            <p>We use natural ingredients like soy wax and essential oils for a clean burn.</p>
            <div className="hover-content">
              <ul>
                <li>Soy-based wax</li>
                <li>Natural dyes</li>
                <li>Clean burning</li>
              </ul>
            </div>
          </div>
          <div className="feature-card animate-on-scroll fade-in" style={{ animationDelay: '0.8s' }}>
            <div className="feature-icon">🎁</div>
            <h3>Versatile Use</h3>
            <p>Perfect for any occasion, from everyday ambiance to special events.</p>
            <div className="hover-content">
              <ul>
                <li>Gift-ready</li>
                <li>Event-specific</li>
                <li>Home decor</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      {/* Journey Section */}
      <section className="journey-section animate-on-scroll slide-up">
        <h2>Our Journey</h2>
        <div className="decorative-line"></div>
        <div className="timeline">
          <div className="timeline-item animate-on-scroll fade-in" style={{ animationDelay: '0.2s' }}>
            <span className="year">2020</span>
            <div className="timeline-content">
              <h3>The Beginning</h3>
              <p>Started as a passion project in a small kitchen, experimenting with scents and designs.</p>
            </div>
          </div>
          <div className="timeline-item animate-on-scroll fade-in" style={{ animationDelay: '0.4s' }}>
            <span className="year">2021</span>
            <div className="timeline-content">
              <h3>Growing Success</h3>
              <p>Expanded our collection and established our first workshop.</p>
            </div>
          </div>
          <div className="timeline-item animate-on-scroll fade-in" style={{ animationDelay: '0.6s' }}>
            <span className="year">2022</span>
            <div className="timeline-content">
              <h3>Innovation</h3>
              <p>Introduced new sustainable practices and custom design services.</p>
            </div>
          </div>
          <div className="timeline-item animate-on-scroll fade-in" style={{ animationDelay: '0.8s' }}>
            <span className="year">2023</span>
            <div className="timeline-content">
              <h3>Today</h3>
              <p>A beloved brand known for quality, creativity, and customer satisfaction.</p>
            </div>
          </div>
        </div>
      </section>

      {/* Sustainability Section */}
      <section className="sustainability-section animate-on-scroll slide-up">
        <div className="section-content">
          <h2>Commitment to Sustainability</h2>
          <div className="decorative-line"></div>
          <div className="sustainability-content">
            <div className="sustainability-text">
              <p>
                We are committed to sustainability in every aspect of our business. From using eco-friendly 
                packaging to selecting renewable resources like soy wax, our focus is on creating products 
                that not only bring joy to our customers but also respect the planet.
              </p>
            </div>
            <div className="eco-badges">
              <div className="eco-badge animate-on-scroll fade-in" style={{ animationDelay: '0.2s' }}>
                <span className="badge-icon">♻️</span>
                <p>Eco-Friendly Packaging</p>
              </div>
              <div className="eco-badge animate-on-scroll fade-in" style={{ animationDelay: '0.4s' }}>
                <span className="badge-icon">🌱</span>
                <p>Sustainable Materials</p>
              </div>
              <div className="eco-badge animate-on-scroll fade-in" style={{ animationDelay: '0.6s' }}>
                <span className="badge-icon">🌍</span>
                <p>Carbon Neutral</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

export default About;
