export const applicationStatuses = {
    new: { label: 'Envoyée', tone: 'tertiary' },
    reviewing: { label: "À l'étude", tone: 'primary' },
    interview: { label: 'Entretien', tone: 'accent' },
    rejected: { label: 'Refusée', tone: 'danger' },
    accepted: { label: 'Acceptée', tone: 'success' },
};

export const recruiterApplicationStatuses = {
    new: { label: 'Nouvelle', tone: 'tertiary' },
    reviewing: { label: "À l'étude", tone: 'primary' },
    interview: { label: 'Entretien', tone: 'accent' },
    rejected: { label: 'Refusée', tone: 'danger' },
    accepted: { label: 'Acceptée', tone: 'success' },
};

export const jobOfferStatuses = {
    draft: { label: 'Brouillon', tone: 'neutral' },
    pending: { label: 'En attente de modération', tone: 'accent' },
    published: { label: 'Publiée', tone: 'success' },
    rejected: { label: 'Refusée', tone: 'danger' },
    filled: { label: 'Pourvue', tone: 'tertiary' },
};

export const roleLabels = {
    candidat: { label: 'Candidat', tone: 'accent' },
    recruteur: { label: 'Recruteur', tone: 'success' },
    admin: { label: 'Administrateur', tone: 'tertiary' },
};

export const contractTypes = ['CDI', 'CDD', 'Freelance', 'Stage'];
