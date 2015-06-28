function mutationChildren = ClustStudMut(parents, options, nvars, ...
    FitnessFcn, state, thisScore, thisPopulation, mutationRate)

mutationChildren = zeros(length(parents),nvars);

for i=1:length(parents)
    child = thisPopulation(parents(i),:);
    % Each child has up to mutationRate student swaps
    thismutationRate = randi(mutationRate);
    for j = 1:thismutationRate
        mutationPoints = randperm(nvars,2);
        child(mutationPoints) = child(mutationPoints([2 1])); %Swap!
    end
    mutationChildren(i,:) = child;
end