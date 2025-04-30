from flask import Flask, request, jsonify
from flask_cors import CORS
from difflib import get_close_matches

app = Flask(__name__)
CORS(app)

# Predefined FAQ Data
faq_data = {
    "How do I report a crime?": "Click 'Register a complaint ' on the homepage and fill in the details.",
    "How can I track my case?": "Log into your account and click 'Track Your Complaint' to view updates.",
    "What is SOS?": "The SOS button notifies the police with your location for immediate help.",
    "Where to register my complaint?": "Click 'Register a complaint' on the homepage and fill in the details.",
    "What types of crimes can I report?": "You can report various crimes including cyber crime, financial fraud, and crimes against women and children.",
    "Is my information confidential?": "Yes, all information shared is strictly confidential and protected.",
    "How long does it take to process a complaint?": "Initial processing typically takes 24-48 hours, after which you'll receive a unqiue userid while signing up keep it save.",
    "Can I report anonymously?": "Yes, you can choose to file an anonymous report for non-emergency situations."
}

def get_best_match(user_question, threshold=0.6):
    # Convert all questions to lowercase for better matching
    matches = get_close_matches(
        user_question.lower(),
        [q.lower() for q in faq_data.keys()],
        n=1,
        cutoff=threshold
    )
    
    if matches:
        # Find the original question with proper capitalization
        for original_q in faq_data.keys():
            if original_q.lower() == matches[0]:
                return original_q
    return None

@app.route("/faq_chatbot", methods=["POST"])
def chatbot():
    try:
        data = request.get_json()

        if not data or "message" not in data:
            return jsonify({"response": "Invalid request. Please ask a proper question."})

        user_input = data["message"].strip()

        if not user_input:  # Prevent empty input
            return jsonify({"response": "Please enter a valid question."})

        best_match = get_best_match(user_input)

        if best_match:
            response = faq_data[best_match]
        else:
            # Provide a more helpful response when no match is found
            response = ("I'm sorry, I couldn't answer that question. "
                      "You can try check our FAQ section or for immediate assistance, please contact our support team at 1800-2222-1111. "
                       )

        return jsonify({"response": response})

    except Exception as e:
        return jsonify({"response": f"Error: {str(e)}"})

if __name__ == '__main__':
    app.run(debug=False, port=5000)  # Added specific port for consistency
