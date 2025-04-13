document.getElementById('baixar-pdf').addEventListener('click', function() {
    // Importando jsPDF
    const { jsPDF } = window.jspdf;

    // Criando uma nova instância do jsPDF
    const doc = new jsPDF();

    // Cabeçalho da carta
    const cabecalho = "Instituição XYZ\nGoverno de Moçambique\nLocalização: Quelimane";

    // Corpo da carta
    const corpo = `
    Prezado(a),

    Este documento confirma que os dados do NUIT requerido são os seguintes:

    Nome: ${document.getElementById("nome").textContent.replace("Nome:", "").trim()}
    NUIT: ${document.getElementById("nuit").textContent.replace("NUIT:", "").trim()}
    Endereço: ${document.getElementById("endereco").textContent.replace("Endereço:", "").trim()}
    Data de Nascimento: ${document.getElementById("data-nascimento").textContent.replace("Data de Nascimento:", "").trim()}
    Tipo de Documento: ${document.getElementById("tipo-documento").textContent.replace("Tipo de Documento:", "").trim()}
    Número do Documento: ${document.getElementById("numero-documento").textContent.replace("Número do Documento:", "").trim()}

    Atenciosamente,
    Autoridade Tributaria D e Mocambique
    `;
    // Adicionando o corpo ao PDF
    doc.text(corpo, 10, 30);

    // Salvando o PDF
    doc.save('nuit_documento.pdf');
});
