import './Footer.css';
import { FaHome, FaTag, FaBoxOpen, FaBlog, FaEnvelope, FaPhone, FaMapMarkerAlt } from 'react-icons/fa';
import { FaFacebookF, FaTwitter, FaInstagram, FaYoutube, FaLinkedinIn, FaTiktok } from 'react-icons/fa';
import logo from '../../assets/logo.png'; // Replace with your logo path

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-container">
        <div className="footer-section">
          <h3><a href="/about">About Us</a></h3>
          <p>
            Nard Candles is dedicated to bringing warmth and light into your home with handcrafted, all-natural candles.
          </p>
        </div>

        <div className="footer-section quick-links">
          <h3>Quick Links</h3>
          <ul>
            
            <li><a href="/"><FaHome /> Home</a></li>
            <li><a href="/blog"><FaBlog /> Posts</a></li>
            <li><a href="/products"><FaBoxOpen /> Products</a></li>
            <li><a href="/customorders"><FaTag /> Custom Orders</a></li>
          </ul>
        </div>

        <div className="footer-section footer-logo">
          <img src={logo} alt="Nard Candles Logo" />
        </div>

        <div className="footer-section">
          <h3>Follow Us</h3>
          <div className="social-media">
            <div className="social-media-row">
              <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                <FaFacebookF />
              </a>
              <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                <FaInstagram />
              </a>
            </div>
            <div className="social-media-row">
              <a href="https://youtube.com" target="_blank" rel="noopener noreferrer">
                <FaYoutube />
              </a>
              <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer">
                <FaTiktok />
              </a>
            </div>
            <div className="social-media-row">
              <a href="https://twitter.com" target="_blank" rel="noopener noreferrer">
                <FaTwitter />
              </a>
              <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer">
                <FaLinkedinIn />
              </a>
            </div>
          </div>
        </div>

        <div className="footer-section contact-info">
          <h3><a href="/contact">Contact Us</a></h3>
          <p>
            <a href="tel:+251933340777"><FaPhone /> (251) 933340777</a><br />

            <a href="mailto:nardosabera248@gmail.com"><FaEnvelope /> nardosabera248@gmail.com</a><br />
            
            <a href="https://www.google.com/maps/place/Alemnesh+Plaza+%7C+Bole+Medhanialem+%7C+%E1%8A%A0%E1%88%88%E1%88%9D%E1%8A%90%E1%88%BD+%E1%8D%95%E1%88%8B%E1%8B%9B+%7C+%E1%89%A6%E1%88%8C+%E1%88%9D%E1%8B%B5%E1%88%90%E1%8A%92%E1%8A%A0%E1%88%88%E1%88%9D/@8.9989144,38.7841602,17z/data=!3m1!4b1!4m6!3m5!1s0x164b85e9d21532d1:0xaa03ce0d1a2ec63d!8m2!3d8.9989091!4d38.7867351!16s%2Fg%2F11pw1q9nxh?entry=ttu&g_ep=EgoyMDI0MDgyOC4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener noreferrer"><FaMapMarkerAlt /> Bole -medhanialem||Alemnesh Plaza||Addis Ababa</a>
          </p>
        </div>
      </div>

      <div className="footer-bottom">
        <p>&copy; {new Date().getFullYear()} Nard Candles. All rights reserved.</p>
        <div className="footer-links">
          <a href="/terms">Terms of Service</a>
          <a href="/privacy">Privacy Policy</a>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
