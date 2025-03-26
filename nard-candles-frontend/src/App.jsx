import { Route, Routes } from 'react-router-dom';
import Header from './components/header/Header'; // Adjust the path as necessary
import Footer from './components/header/Footer'; // Adjust the path as necessary
import Home from './pages/Home';
import Products from './pages/Products';
import CustomOrders from './pages/CustomOrders';
import Post from './pages/post';
import Contact from './pages/Contact';
import About from './pages/About';
import TermsOfService from './components/header/TermsOfService';
import PrivacyPolicy from './components/header/PrivacyPolicy';
import MyCart from './pages/MyCart';
import RegistrationPage from "./pages/RegistrationPage";
function App() {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/products" element={<Products />} />
        <Route path="/custom-orders" element={<CustomOrders />} />
        <Route path="/blog" element={<Post />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/about" element={<About />} />
        <Route path="/terms" element={<TermsOfService />} />
        <Route path="/privacy" element={<PrivacyPolicy />} />
        <Route path="/my-cart" element={<MyCart />} />
        <Route path="/register" element={<RegistrationPage />} />
      </Routes>
      <Footer />
    </>
  );
}

export default App;
