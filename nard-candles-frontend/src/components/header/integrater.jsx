import  { useState } from 'react';
import Modal from './Modal';
import PrivacyPolicy from './PrivacyPolicy';
import TermsOfService from './TermsOfService';

const App = () => {
const [isPrivacyPolicyOpen, setPrivacyPolicyOpen] = useState(false);
const [isTermsOfServiceOpen, setTermsOfServiceOpen] = useState(false);

return (
    <div>
    <button onClick={() => setPrivacyPolicyOpen(true)}>Privacy Policy</button>
    <button onClick={() => setTermsOfServiceOpen(true)}>Terms of Service</button>

    <Modal isOpen={isPrivacyPolicyOpen} onClose={() => setPrivacyPolicyOpen(false)}>
        <PrivacyPolicy />
    </Modal>

    <Modal isOpen={isTermsOfServiceOpen} onClose={() => setTermsOfServiceOpen(false)}>
        <TermsOfService />
    </Modal>
    </div>
);
};

export default App;
