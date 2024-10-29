import './About.css';

const About = () => {
  return (
    <div className="about-container">
      <section className="about-section fade-in">
        <h1>About Us</h1>
        <p>
          Welcome to <strong>Nard Candles</strong>, where luxury meets craftsmanship. We specialize in creating and selling luxury scented candles that are designed to elevate any space with their premium fragrances and elegant, handcrafted designs. Our commitment to quality and sustainability ensures that each candle we produce not only looks beautiful but also burns cleanly, offering a lasting aroma that transforms your environment.
        </p>
      </section>

      <section className="mission-section fade-in">
        <h2>Our Mission</h2>
        <p>
          At Nard Candles, we believe that a well-crafted candle can do more than just provide light; it can enhance your home, create memorable moments, and offer a touch of luxury to everyday life. Our mission is to bring warmth, comfort, and elegance to every room through our meticulously designed candles.
        </p>
      </section>

      <section className="unique-section fade-in">
        <h2>What Sets Us Apart</h2>
        <ul>
          <li><strong>Premium Fragrances:</strong> We carefully select high-quality fragrances to ensure that our candles offer a rich, lasting scent that fills your space with warmth and character.</li>
          <li><strong>Handcrafted Designs:</strong> Each of our candles is handcrafted with precision and care, resulting in unique, elegant products that stand out in both aesthetics and performance.</li>
          <li><strong>Natural Ingredients:</strong> We use natural ingredients such as soy wax and essential oils to provide a clean burn that’s better for you and the environment. Our candles are free from harmful chemicals, ensuring a safer, healthier atmosphere in your home.</li>
          <li><strong>Versatile Use:</strong> Whether you’re looking to enhance your living space, find the perfect gift, or add a special touch to an event, our candles are designed to meet your needs.</li>
        </ul>
      </section>

      <section className="journey-section fade-in">
        <h2>Our Journey</h2>
        <p>
          Founded in 2020, Nard Candles started as a passion project in a small kitchen. Our love for creating something special led us to experiment with various scents and designs, eventually growing into the beloved brand we are today. From our humble beginnings to becoming a name synonymous with luxury and quality, our journey has been fueled by a dedication to our craft and a desire to share the beauty of a well-made candle with the world.
        </p>
      </section>

      <section className="sustainability-section fade-in">
        <h2>Commitment to Sustainability</h2>
        <p>
          We are committed to sustainability in every aspect of our business. From using eco-friendly packaging to selecting renewable resources like soy wax, our focus is on creating products that not only bring joy to our customers but also respect the planet.
        </p>
      </section>
    </div>
  );
};

export default About;
