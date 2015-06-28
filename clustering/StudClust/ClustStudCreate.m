function Population = ClustStudCreate(GenomeLength,FitnessFcn,options,nClust)

totalPopulation = sum(options.PopulationSize);
initPopProvided = size(options.InitialPopulation,1);
individualsToCreate = totalPopulation - initPopProvided;

%Master individual
alpha = rem(GenomeLength,nClust);
smallclust = floor(GenomeLength/nClust);
largeclust = smallclust+1;
smalldat = repmat(1:(nClust-alpha),smallclust);
largedat = repmat((nClust-alpha+1):nClust,largeclust);
masterindividual = [smalldat(:); largedat(:)]';

% Initialize Population to be created
Population = zeros(totalPopulation,GenomeLength);
% Use initial population provided already
if initPopProvided > 0
    Population(1:initPopProvided,:) = options.InitialPopulation;
end

cnt = initPopProvided+1;
for i = 1:individualsToCreate
    Population(cnt,:) = masterindividual(randperm(GenomeLength));
    cnt = cnt + 1;
end
